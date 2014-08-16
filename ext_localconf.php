<?php

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_fluidpage_pi1.php','_pi1','list_type',1);

t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fluidpage/static/tsconfig/pageTSConfig.txt">');

if(!empty($GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"])) {
	$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= ','."backend_layout,backend_layout_next_level";
} else {
	$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= "backend_layout,backend_layout_next_level";
}

if(!function_exists('user_activeTemplateIsOneOf')) {
	/**
	 * @param $templateUid
	 * @return bool
	 */
	function user_activeBackendLayoutIs($templateUid) {
		return t3lib_div::makeInstance('Tx_Extbase_Object_Manager')->get('Tx_Fluidpage_Typoscript_Conditions')->activeBackendLayoutIs($templateUid) ? true : false;
	}
}