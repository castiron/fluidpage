<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY,'pi1/class.tx_fluidpage_pi1.php','_pi1','list_type',1);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fluidpage/static/tsconfig/pageTSConfig.txt">');

if(!empty($GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"])) {
	$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= ','."backend_layout,backend_layout_next_level";
} else {
	$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= "backend_layout,backend_layout_next_level";
}

if(!function_exists('user_activeBackendLayoutIs')) {
	/**
	 * @param $templateUid
	 * @return bool
	 */
	function user_activeBackendLayoutIs($templateUid) {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')->get('Tx_Fluidpage_Typoscript_Conditions')->activeBackendLayoutIs($templateUid) ? true : false;
	}
}