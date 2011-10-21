<?php

class tx_fluidpage_pi1 extends tslib_pibase {

	public $prefixId = 'tx_fluidpage_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_fluidpage_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey = 'fluidpage';	// The extension key.

	function main($content,$conf) {
		$templateFactory = new Tx_Fluidpage_Service_TemplateFactory($conf);
		$templateObject = $templateFactory->getTemplateFromRootline($GLOBALS['TSFE']->rootLine);
		$templateController = new Tx_Fluidpage_Controller_Template($conf['settings.'],$this->cObj);
		$templateController->setTemplate($templateObject);
		return $templateController->render();
	}

//
//
//		// check if the needed extensions are installed
//		if (!t3lib_extMgm::isLoaded('fluid')) {
//			return 'You need to install "Fluid" in order to use the FLUIDTEMPLATE content element';
//		}
//
//		// set class properties
//		$this->setContent($content);
//		$this->setConf($conf);
//		$this->setPageUID($GLOBALS['TSFE']->id);
//
//		// determine what template file we're using
//		$layoutUID = $this->determineLayoutUid();
//
//		$this->setLayoutRecord($layoutUID);
//
//		$templateFile = $this->determineTemplateFile($layoutUID);
//
//		// make the view
//		$view = $this->constructView($templateFile);
//		$this->assignTyposcriptVariables($view);
//
//		// get layout constants
//		$constants = $this->conf['templates.'][$layoutUID.'.']['constants.'];
//		if(!is_array($constants)) $constants = array();
//
//		// get global constants
//		$gConstants = $this->conf['constants.'];
//		if(!is_array($gConstants)) $gConstants = array();
//
//		$constants = array_merge($gConstants,$constants);
//
//		// fluidpage variable assignment
//		$view->assign('data', $this->cObj->data); // legacy; remove it at some point.
//		$view->assign('page', $this->cObj->data);
//		$view->assign('current', $this->cObj->data[$this->cObj->currentValKey]);
//		$view->assign('layout', $layoutUID);
//		$view->assign('layoutClass', $this->getLayoutClassName());
//		$view->assign('constants',$constants);
//		$view->assign('constants',$constants);
//
//		// render the view and assign output to $this->content;
//		$this->setContent($view->render());
//
//		// postprocess content
//		$this->parseContentPostRender();
//
//		// output the results
//		return $this->getContent();



}
?>