<?php

class Tx_Fluidpage_Controller_Template {

	private $defaultFormat = 'html';
	private $reservedVariableConstantNames = array('data', 'current', 'page');
	private $template;
	private $configuration = array();
	private $view;
	private $cObj;

	/**
	 * Construct method for the controller
	 * @param $configuration
	 * @param $cObj
	 */
	public function __construct($configuration,$cObj) {
		$this->configuration = $configuration;
		$this->cObj = $cObj;
	}

	/**
	 * The main method on the controller, it sets up the view, initializes it, and outputs it.
	 * @return string
	 */
	public function render() {
		$this->view = $this->createView();
		$this->configureViewTemplateSource();
		$this->configureViewLayoutPath();
		$this->configureViewPartialPath();
		$this->configureViewFormat();
		$this->configureViewAssignVariables();
		$this->configureViewAssignConstants();
		$output = $this->getViewOutput();
		return $output;
	}

	/**
	 * Sets the template object for this execution
	 * @param $template Tx_Fluidpage_Model_Template
	 * @return void
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * Get the template for the current execution
	 * @return Tx_Fluidpage_Model_Template the template object that's being rendered
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Instantiates a Fluid view
	 * @param $template
	 * @return Tx_Fluid_View_StandaloneView
	 */
	protected function createView($template) {
		$view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		return $view;
	}

	/**
	 * Sets the HTML source returned by the template model on the Fluid view object
	 * @return void
	 */
	protected function configureViewTemplateSource() {
		$this->view->setTemplateSource($this->getTemplate()->getBody());
	}

	/**
	 * Sets the partial path on the Fluid view object from the corresponding value in settings Typoscript
	 * @return void
	 */
	protected function configureViewPartialPath() {
		// set partial path
		$partialRootPath = t3lib_div::getFileAbsFileName($this->configuration['partialRootPath']);
		if($partialRootPath) {
			$this->view->setPartialRootPath($partialRootPath);
		}
	}
	
	protected function configureViewLayoutPath() {
		$layoutRootPath = t3lib_div::getFileAbsFileName($this->configuration['layoutRootPath']);
		if($layoutRootPath) {
			t3lib_div::debug($layoutRootPath);
			$this->view->setLayoutRootPath($layoutRootPath);
		}
	}
	

	/**
	 * Merges global constants and template constants and assigns them to the Fluid view object
	 * @throws InvalidArgumentException
	 * @return void
	 */
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

	/**
	 * Merges global variables and template variables and assigns them to the Fluid view object.
	 * Also assigns fixed fluidpage variables to the view object.
	 * @throws InvalidArgumentException
	 * @return void
	 */
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

	/**
	 * Returns the rendered view output. We need to remove the closing body tag, as TYPO3 will put that in when
	 * it renders the page.
	 * @return string
	 */
	protected function getViewOutput() {
 		$output = $this->view->render();
		$output = str_replace('</body>','',$output);
		return $output;
	}

	/**
	 * Gets the output format from the template and sets it on the view.
	 * @return void
	 */
	protected function configureViewFormat() {
		// set format
		$format = $this->getTemplate()->getFormat();
		if($format) {
			$this->view->setFormat($format);
		} else {
			$this->view->setFormat($this->defaultFormat);
		}
	}

}

?>