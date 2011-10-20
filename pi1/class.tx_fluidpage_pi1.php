<?php

class tx_fluidpage_pi1 extends tslib_pibase {

	public $prefixId = 'tx_fluidpage_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_fluidpage_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey = 'fluidpage';	// The extension key.

	protected $content = FALSE;
	public $conf = array();
	protected $pageUID = FALSE;
	protected $layoutRecord = FALSE;
	
	protected function setContent($content) {
		$this->content = $content;
	}
	
	protected function getContent() {
		return $this->content;
	}

	protected function setConf($conf) {
		$this->conf = $conf;
	}	
	
	protected function setPageUID($pageUID) {
		$this->pageUID = $pageUID;
	}
	
	protected function getPageUID() {
		return $this->pageUID;
	}

	protected function determineLayoutUid() {

		$layoutUID = 0;
		$pageUID = $this->getPageUID();

		$rootLine = $GLOBALS['TSFE']->rootLine;

		if (is_array ($rootLine)) {

			$thisLevel = count($rootLine) - 1;
			foreach ($rootLine as $level => $page) {
				if ($page['backend_layout']) {
					$crawl['templateRec'] = $page['backend_layout'];
					$crawl['templateRecLevel'] = $level;
					break;
				}
			}
			$i = 1;
			foreach ($rootLine as $level => $page) {
				if($i == 1) {
					$i++;
					continue;
				}
				if ($page['backend_layout_next_level']) {
					$crawl['childTemplateRec'] = $page['backend_layout_next_level'];
					$crawl['childTemplateRecLevel'] = $level;
					break;
				}
			}

			if($crawl['templateRec']) {
				if ($crawl['templateRecLevel'] == $thisLevel) {
					$layoutUID = $crawl['templateRec'];
				} elseif($crawl['childTemplateRec']) {
					if($crawl['childTemplateRecLevel'] >= $crawl['templateRecLevel']) {
						$layoutUID = $crawl['childTemplateRec'];
					}
					if($crawl['childTemplateRecLevel'] < $crawl['templateRecLevel']) $layoutUID = $crawl['templateRec'];
				} else {
					$layoutUID = $crawl['templateRec'];
				}
			}

		}
		return $layoutUID;
	}
	
	protected function determineTemplateFile($layoutUID) {
		$file = $this->conf['templates.'][$layoutUID.'.']['file'];
		return $file;
	}

	function main($content,$conf) {
		
		// check if the needed extensions are installed
		if (!t3lib_extMgm::isLoaded('fluid')) {
			return 'You need to install "Fluid" in order to use the FLUIDTEMPLATE content element';
		}
		
		// set class properties
		$this->setContent($content);
		$this->setConf($conf);
		$this->setPageUID($GLOBALS['TSFE']->id);
		
		// determine what template file we're using
		$layoutUID = $this->determineLayoutUid();

		$this->setLayoutRecord($layoutUID);

		$templateFile = $this->determineTemplateFile($layoutUID);

		// make the view
		$view = $this->constructView($templateFile);
		$this->assignTyposcriptVariables($view);
		
		// fluidpage variable assignment
		$view->assign('data', $this->cObj->data);
		$view->assign('current', $this->cObj->data[$this->cObj->currentValKey]);
		$view->assign('layout', $layoutUID);
		$view->assign('layoutClass', $this->getLayoutClassName());

		// render the view and assign output to $this->content;
		$this->setContent($view->render());

		// postprocess content
		$this->parseContentPostRender();

		// output the results
		return $this->getContent();
	}

	protected function getLayoutClassName() {
		return $this->layoutRecord['tx_fluidpage_body_class'];
	}
	
	protected function setLayoutRecord($layoutUID) {
		$rows = array();
		$select = '*';
		$table = 'backend_layout';
		$where = 'backend_layout.uid = '.$GLOBALS['TYPO3_DB']->fullQuoteStr($layoutUID,$table);
		$limit = '1';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$this->layoutRecord = $row;
	}
	
	protected function parseContentPostRender() {
		$content = $this->getContent();
		if(isset($this->conf['stdWrap.'])) {
			$this->setContent($this->cObj->stdWrap($content, $this->conf['stdWrap.']));
		}
		$content = str_replace('</body>','',$content); 
		$this->setContent($content);
	}

	protected function assignTyposcriptVariables($view) {
		$reservedVariables = array('data', 'current');
			// accumulate the variables to be replaced
			// and loop them through cObjGetSingle
		$variables = (array) $this->conf['variables.'];
		foreach ($variables as $variableName => $cObjType) {
			if (is_array($cObjType)) {
				continue;
			}
			if(!in_array($variableName, $reservedVariables)) {
				$view->assign($variableName, $this->cObj->cObjGetSingle($cObjType, $variables[$variableName . '.']));
			} else {
				throw new InvalidArgumentException('Cannot use reserved name "' . $variableName . '" as variable name in FLUIDTEMPLATE.', 1288095720);
			}
		}
	}

	protected function extractBody($templateFile) {
		$path = t3lib_div::getFileAbsFileName($templateFile);
		$content = file_get_contents($path);

		$this->htmlParse = t3lib_div::makeInstance('t3lib_parsehtml');
		$layout = $this->htmlParse->getSubpart($content,'###LAYOUT###');

		return $layout;
	}

	protected function constructView($templateFile) {
		
		$body = $this->extractBody($templateFile);
		$view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		$view->setTemplateSource($body);

		// override the default layout path via typoscript
		$layoutRootPath = $this->conf['settings.']['layoutRootPath'];
		if($layoutRootPath) {
			$layoutRootPath = t3lib_div::getFileAbsFileName($layoutRootPath);
			$view->setLayoutRootPath($layoutRootPath);
		}

			// override the default partials path via typoscript
		$partialRootPath = $this->conf['settings.']['partialRootPath'];
		if($partialRootPath) {
			$partialRootPath = t3lib_div::getFileAbsFileName($partialRootPath);
			$view->setPartialRootPath($partialRootPath);
		}

			// override the default format
		$format = $this->conf['format'];
		if ($format) {
			$view->setFormat($format);
		}

		$requestControllerExtensionName = isset($this->conf['extbase.']['controllerExtensionName'])
			? $this->cObj->stdWrap($this->conf['extbase.']['controllerExtensionName'], $this->conf['extbase.']['controllerExtensionName'])
			: $this->conf['extbase.']['controllerExtensionName'];
		if($requestControllerExtensionName) {
			$view->getRequest()->setControllerExtensionName($requestControllerExtensionName);
		}

		$requestControllerName = isset($this->conf['extbase.']['controllerName'])
			? $this->cObj->stdWrap($this->conf['extbase.']['controllerName'], $this->conf['extbase.']['controllerName'])
			: $this->conf['extbase.']['controllerName'];
		if($requestControllerName) {
			$view->getRequest()->setControllerName($requestControllerName);
		}

		$requestControllerActionName = isset($this->conf['extbase.']['controllerActionName'])
			? $this->cObj->stdWrap($this->conf['extbase.']['controllerActionName'], $this->conf['extbase.']['controllerActionName'])
			: $this->conf['extbase.']['controllerActionName'];
		if($requestControllerActionName) {
			$view->getRequest()->setControllerActionName($requestControllerActionName);
		}

		return $view;
	}

}
?>