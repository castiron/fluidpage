<?php

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_fluidpage_pi1.php','_pi1','list_type',1);

t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fluidpage/static/tsconfig/pageTSConfig.txt">');


$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= 'backend_layout,backend_layout_next_level';

?>