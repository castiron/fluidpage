<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY,'static/fluid_page_static_typoscript_template/', 'Fluid Page Static Typoscript Template');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/fluid_page_sample_page_templates/', 'Fluid Page Sample Page Templates');
t3lib_extMgm::addPlugin(array('LLL:EXT:'.$_EXTKEY.'/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


?>