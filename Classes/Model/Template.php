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

	/**
	 * The template model constructor method
	 * @param $layoutUid
	 * @param $configuration
	 */
	public function __construct($layoutUid, $configuration) {
		$this->layoutUid = $layoutUid;
		$this->configuration = $configuration;
	}

	/**
	 * Returns the value of the file typoscript setting for this template.
	 * @return string
	 */
	public function getFileName() {
		return $this->configuration['file'];
	}

	/**
	 * Returns the format of the template. Will default to html in the controller of not set.
	 * @return string
	 */
	public function getFormat() {
		return $this->configuration['format'];
	}

	/**
	 * Returns the variables section of the template's typoscript config.
	 * @return array
	 */
	public function getVariables() {
		return $this->configuration['variables.'];
	}

	/**
	 * Returns the constants section of the template's typoscript config.
	 * @return
	 */
	public function getConstants() {
		return $this->configuration['constants.'];
	}

	/**
	 * Returns the layout UID for this template.
	 * @return int
	 */
	public function getLayoutUid() {
		return $this->layoutUid;
	}

	/**
	 * Reads the template file and returns whatever is between the layout markers.
	 * TODO: Improve this so that the layout markers are no longer necessary.
	 * @return string
	 */
	public function getBody() {
		$templateFile = $this->getFileName();
		$path = t3lib_div::getFileAbsFileName($templateFile);
		$content = file_get_contents($path);
		$htmlParse = t3lib_div::makeInstance('t3lib_parsehtml');
		$body = $htmlParse->getSubpart($content,'###LAYOUT###');
		return $body;
	}

}
