<?php namespace CIC\Fluidpage\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class ifContentInColumnViewHelper
 * @package CIC\Fluidpage\ViewHelpers
 */
class IfContentInColumnViewHelper extends AbstractConditionViewHelper {
    protected $escapingInterceptorEnabled = FALSE;

    /**
     * Fetches content from a column, slides possible, and aliases it inside an if block.
     * If the content exists, it will be rendered, if not, nothing inside the block will get rendered.
     * Usage:
     *
    <fp:ifContentInColumn colPos="2">
        <section class="super-padding">
            {content}
        </section>
    </fp:ifContentInColumn>
     *
     * @param integer $colPos The colPos value in the db
     * @param integer $slide The value of the slide
     * @param integer $pageUid The page id to get content from
     * @param boolean $searchIndexWrap Wrap the output with search indexing tags
     * @param string $alias The alias of the content if it's found
     * @return String The content
     * @author Gabe Blair
     */
    public function render($colPos, $slide = 0, $pageUid = 0, $searchIndexWrap = true, $alias = 'content') {
        $type = 'CONTENT';
        $conf = array(
            'table' => 'tt_content',
            'select.' => array(
                'orderBy' => 'sorting',
                'where' => 'colPos=' . $colPos,
                'languageField' => 'sys_language_uid'
            )
        );
        if($pageUid) {
            $conf['select.']['pidInList'] = $pageUid;
        }

        if($slide) {
            $conf['slide'] = $slide;
        }

        if ($out = $GLOBALS['TSFE']->cObj->cObjGetSingle($type, $conf)) {
            if($searchIndexWrap) {
                $out = "<!--TYPO3SEARCH_begin-->" . $out . "<!--TYPO3SEARCH_end-->";
            }
            $this->templateVariableContainer->add($alias, $out);
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}
