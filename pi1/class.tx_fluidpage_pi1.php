<?php

class tx_fluidpage_pi1 {

	public $prefixId = 'tx_fluidpage_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_fluidpage_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey = 'fluidpage';	// The extension key.

	/**
	 * Bootstraps the FluidPage rendering process.
	 *
	 * @param $content
	 * @param $conf
	 * @return mixed
	 */
	function main($content,$conf) {
		$templateFactory = new Tx_Fluidpage_Service_TemplateFactory($conf);
		$templateObject = $templateFactory->getTemplateFromRootline($GLOBALS['TSFE']->rootLine);
		$templateController = new Tx_Fluidpage_Controller_Template($conf['settings.'],$this->cObj);
		$templateController->setTemplate($templateObject);
		return $templateController->render();
	}

}
