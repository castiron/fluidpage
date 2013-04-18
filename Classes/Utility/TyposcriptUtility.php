<?php
class Tx_Fluidpage_Utility_TyposcriptUtility implements t3lib_Singleton {

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
		$this->templateFactory = t3lib_div::makeInstance('Tx_Fluidpage_Service_TemplateFactory');
	}

	/**
	 * @param $content
	 * @param $conf
	 * @return bool
	 * @throws Tx_Extbase_Configuration_Exception
	 */
	public function currentTemplateIsOneOf($content, $conf) {
		$out = false;
		$uids = t3lib_div::trimExplode(',', $conf['uids']);
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
?>