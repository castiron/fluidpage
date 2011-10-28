<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zdavis
 * Date: 10/21/11
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */
 
class Tx_Fluidpage_Service_TemplateFactory {

	private $configuration = array();

	/**
	 * Template factory constructor. The factory needs the fluidpage configuration to determine which template model to
	 * create.
	 * @param $configuration
	 */
	public function __construct($configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * Takes the rootline as an argument and uses it to determine which template model to return for the current page.
	 * @param $rootline
	 * @return Tx_Fluidpage_Model_Template
	 */
	public function getTemplateFromRootline($rootline) {
		$layoutUid = $this->getLayoutUidFromRootline($rootline);
		$templateModel = $this->getTemplateModelFromLayoutUid($layoutUid);
		return $templateModel;
	}

	/**
	 * Takes a layout UID as an argument and instantiates and returns the corresponding template.
	 * @param $uid
	 * @return Tx_Fluidpage_Model_Template
	 */
	protected function getTemplateModelFromLayoutUid($uid) {
		$templateConf = $this->configuration['templates.'][$uid.'.'];
		$templateModel = new Tx_Fluidpage_Model_Template($uid, $templateConf);
		return $templateModel;
	}

	/**
	 * This method walks the rootline and makes the determination about which templateUID to use for the current page.
	 * @param $rootLine
	 * @return
	 */
	protected function getLayoutUidFromRootline($rootLine) {
		if (is_array ($rootLine)) {
			$thisLevel = count($rootLine) - 1;
			foreach ($rootLine as $level => $page) {
				if ($page['backend_layout']) {
					$crawl['templateRec'] = $page['backend_layout'];
					$crawl['templateRecLevel'] = $level;
					break;
				}
			}
			$i = 1;
			foreach ($rootLine as $level => $page) {
				if($i == 1) {
					$i++;
					continue;
				}
				if ($page['backend_layout_next_level']) {
					$crawl['childTemplateRec'] = $page['backend_layout_next_level'];
					$crawl['childTemplateRecLevel'] = $level;
					break;
				}
			}

			if($crawl['templateRec']) {
				if ($crawl['templateRecLevel'] == $thisLevel) {
					$layoutUID = $crawl['templateRec'];
				} elseif($crawl['childTemplateRec']) {
					if($crawl['childTemplateRecLevel'] >= $crawl['templateRecLevel']) {
						$layoutUID = $crawl['childTemplateRec'];
					}
					if($crawl['childTemplateRecLevel'] < $crawl['templateRecLevel']) $layoutUID = $crawl['templateRec'];
				} else {
					$layoutUID = $crawl['templateRec'];
				}
			}

		}
		return $layoutUID;
	}

}

?>
