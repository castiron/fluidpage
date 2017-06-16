<?php namespace CIC\Fluidpage\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class IfTemplateViewHelper extends AbstractConditionViewHelper {

    /**
     * @return mixed|string
     */
    public function render() {
        $templateUids = $this->arguments['templateUids'];
        return $this->listContainsActiveTemplateUid($templateUids) ?
            $this->renderThenChild() :
            $this->renderElseChild();
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
