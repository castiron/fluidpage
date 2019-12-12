<?php namespace CIC\Fluidpage\Typoscript;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class Conditions
 */
class Conditions {

	/**
	 * @var \CIC\Fluidpage\Service\TemplateFactory
	 */
	var $templateFactory;

	/**
	 * Need to pass an argument to TemplateFactory constructorc
	 */
	public function initializeObject() {
		$this->templateFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(TemplateFactory::class, array());
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
        $rootLine = GeneralUtility::makeInstance(RootlineUtility::class)->get();
		return $this->templateFactory->getTemplateFromRootline($rootLine)->getLayoutUid();
	}
}
