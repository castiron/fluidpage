<?php


$extensionPath = t3lib_extMgm::extPath('fluidpage');
$extensionClassesPath = $extensionPath . 'Classes/';
return array(
	'tx_fluidpage_controller_template' => $extensionClassesPath . 'Controller/Template.php',
	'tx_fluidpage_pi1' => $extensionClassesPath . 'Controller/tmp.php',
	'tx_fluidpage_model_template' => $extensionClassesPath . 'Model/Template.php',
	'tx_fluidpage_service_templatefactory' => $extensionClassesPath . 'Service/TemplateFactory.php',
	'tx_fluidpage_viewhelpers_contentviewhelper' => $extensionClassesPath . 'ViewHelpers/ContentViewHelper.php',
);
?>
