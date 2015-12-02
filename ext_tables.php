<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY,'static/fluid_page_static_typoscript_template/', 'Fluid Page Static Typoscript Template');

# Fluid pages has a pi1, but it's not a plugin that can be inserted on a page.
#t3lib_extMgm::addPlugin(array('LLL:EXT:'.$_EXTKEY.'/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');

?>