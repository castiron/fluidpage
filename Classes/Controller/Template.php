<?php

class Tx_Fluidpage_Controller_Template {

	private $defaultFormat = 'html';
	private $reservedVariableConstantNames = array('data', 'current', 'page');
	private $template;
	private $configuration = array();
	private $view;
	private $cObj;

	public function __construct($configuration,$cObj) {
		$this->configuration = $configuration;
		$this->cObj = $cObj;
	}

	public function setTemplate($template) {
		$this->template = $template;
	}

	public function getTemplate() {
		return $this->template;
	}

	protected function createView($template) {
		$view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		return $view;
	}

	protected function configureViewTemplateSource() {
		$this->view->setTemplateSource($this->getTemplate()->getBody());
	}

	protected function configureViewPartialPath() {
		// set partial path
		$partialRootPath = t3lib_div::getFileAbsFileName($this->configuration['partialRootPath']);
		if($partialRootPath) {
			$this->view->setPartialRootPath($partialRootPath);
		}
	}

	protected function configureViewAssignConstants() {
		$globalConstants = $this->configuration['constants.'];
		$templateConstants = $this->getTemplate()->getConstants();
		if(!is_array($globalConstants)) $globalConstants = array();
		if(!is_array($templateConstants)) $templateConstants = array();
		$constants = array_merge($globalConstants, $templateConstants);
		foreach($constants as $constant => $value) {
			if(in_array($constant, $this->reservedVariableConstantNames)) {
				unset($constants[$constant]);
				throw new InvalidArgumentException('Cannot use reserved name "' . $constant . '" as variable name in FLUIDPAGE.', 1288095721);
			}
		}
		$this->view->assign('constants',$constants);
	}

	protected function configureViewAssignVariables() {
		$globalVariables = $this->configuration['variables.'];
		$templateVariables = $this->getTemplate()->getVariables();
		if(!is_array($globalVariables)) $globalVariables = array();
		if(!is_array($templateVariables)) $templateVariables = array();
		$variables = array_merge($globalVariables, $templateVariables);

		foreach ($variables as $variableName => $cObjType) {
			if (is_array($cObjType)) {
				continue;
			}
			if(!in_array($variableName, $this->reservedVariableConstantNames)) {
				$this->view->assign($variableName, $this->cObj->cObjGetSingle($cObjType, $variables[$variableName . '.']));
			} else {
				throw new InvalidArgumentException('Cannot use reserved name "' . $variableName . '" as variable name in FLUIDPAGE.', 1288095721);
			}
		}
		$this->view->assign('data', $this->cObj->data);
		$this->view->assign('page', $this->cObj->data);
		$this->view->assign('current', $this->cObj->data[$this->cObj->currentValKey]);
		$this->view->assign('layout', $this->template->getLayoutUid());
	}

	protected function getViewOutput() {
 		$output = $this->view->render();
		$output = str_replace('</body>','',$output);
		return $output;
	}

	protected function configureViewFormat() {
		// set format
		$format = $this->getTemplate()->getFormat();
		if($format) {
			$this->view->setFormat($format);
		} else {
			$this->view->setFormat($this->defaultFormat);
		}
	}

	public function render() {
		$this->view = $this->createView();
		$this->configureViewTemplateSource();
		$this->configureViewPartialPath();
		$this->configureViewFormat();
		$this->configureViewAssignVariables();
		$this->configureViewAssignConstants();

		$output = $this->getViewOutput();
		return $output;
	}
}

?>