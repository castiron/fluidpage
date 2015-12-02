<?php

/**
 * Class Tx_Fluidpage_Typoscript_Conditions
 */
class Tx_Fluidpage_Typoscript_Conditions {

	/**
	 * @var Tx_Fluidpage_Service_TemplateFactory
	 */
	var $templateFactory;

	/**
	 * Need to pass an argument to TemplateFactory constructorc
	 */
	public function initializeObject() {
		$this->templateFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_Extbase_Object_Manager')->create('Tx_Fluidpage_Service_TemplateFactory', array());
	}

	/**
	 * @param $backendLayoutUid
	 * @return bool
	 */
	public function activeBackendLayoutIs($backendLayoutUid) {
		$active = $this->getActiveBackendLayout();
		return $backendLayoutUid == $active;
	}

	/**
	 * @return string
	 */
	protected function getActiveBackendLayout() {
		return $this->templateFactory->getTemplateFromRootline($GLOBALS['TSFE']->rootLine)->getLayoutUid();
	}

}