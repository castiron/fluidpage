<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zdavis
 * Date: 10/21/11
 * Time: 9:53 AM
 * To change this template use File | Settings | File Templates.
 */
 
class Tx_Fluidpage_Model_Template {

	private $configuration = array();
	private $layoutUid = 0;

	public function __construct($layoutUid, $configuration) {
		$this->layoutUid = $layoutUid;
		$this->configuration = $configuration;
	}

	public function getFileName() {
		return $this->configuration['file'];
	}

	public function getFormat() {
		return $this->configuration['format'];
	}

	public function getVariables() {
		return $this->configuration['variables.'];
	}

	public function getConstants() {
		return $this->configuration['constants.'];
	}

	public function getLayoutUid() {
		return $this->layoutUid;
	}

	public function getBody() {
		$templateFile = $this->getFileName();
		$path = t3lib_div::getFileAbsFileName($templateFile);
		$content = file_get_contents($path);
		$htmlParse = t3lib_div::makeInstance('t3lib_parsehtml');
		$body = $htmlParse->getSubpart($content,'###LAYOUT###');
		return $body;
	}

}
