<?php namespace CIC\Fluidpage\ViewHelpers;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
	* Content view helper
	*
	* @package fluidpage
	* @subpackage
	* @version
	*/
class IfTemplateViewHelper extends AbstractConditionViewHelper {

	/**
	 * @param string $templateUids
	 * @return string
	 */
	public function render($templateUids) {
		if ($this->listContainsActiveTemplateUid($templateUids)) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}

	/**
	 * @param $templateUids
	 * @return bool
	 */
	protected function listContainsActiveTemplateUid($templateUids) {
		return true;
	}

	protected function getActiveTemplate() {

	}
}
