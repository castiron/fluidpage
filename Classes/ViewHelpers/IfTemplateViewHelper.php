<?php
/**
	* Content view helper
	*
	* @package fluidpage
	* @subpackage
	* @version
	*/
class Tx_Fluidpage_ViewHelpers_IfTemplateViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

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