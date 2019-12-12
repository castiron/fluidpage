<?php namespace CIC\Fluidpage\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class Typoscript implements t3lib_Singleton {

//	/**
//	 * @var Tx_Fluidpage_Service_TemplateFactory
//	 */
//	var $templateFactory;
//
//	/**
//	 * @param Tx_Fluidpage_Service_TemplateFactory $templateFactory
//	 */
//	public function injectTemplateFactory(Tx_Fluidpage_Service_TemplateFactory $templateFactory) {
//		$this->templateFactory = $templateFactory;
//	}

	public function __construct() {
		$this->templateFactory = GeneralUtility::makeInstance('Tx_Fluidpage_Service_TemplateFactory');
	}

	/**
	 * @param $content
	 * @param $conf
	 * @return bool
	 * @throws
	 */
	public function currentTemplateIsOneOf($content, $conf) {
		$out = false;
		$uids = GeneralUtility::trimExplode(',', $conf['uids']);
		if(count($uids) && $uids[0]) {
			$template = $this->templateFactory->getTemplateFromRootline($GLOBALS['TSFE']->rootLine);
			$layoutUid = $template->getLayoutUid();
			foreach($uids as $uid) {
				if($uid == $layoutUid) {
					$out = true;
					break;
				}
			}
		}
		return $out;
	}
}
