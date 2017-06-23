<?php namespace CIC\Fluidpage\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Content view helper
 *
 * @package fluidpage
 * @subpackage
 * @version
 */
class ContentViewHelper extends AbstractViewHelper {

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * Fetches content from a column, slides possible
     *
     * @param integer $colPos The colPos value in the db
     * @param integer $slide The value of the slide
     * @param integer $pageUid The page id to get content from
     * @param boolean $searchIndexWrap Wrap the output with search indexing tags
     * @return String The content
     * @author Lucas Thurston
     */
    public function render($colPos, $slide = 0, $pageUid = 0, $searchIndexWrap = true) {
        $conf = array(
            'table' => 'tt_content',
            'select.' => array(
                'orderBy' => 'sorting',
                'where' => 'colPos='.$colPos,
                'languageField' => 'sys_language_uid'
            )
        );

        if($pageUid) {
            $conf['select.']['pidInList'] = $pageUid;
        }

        if($slide) {
            $conf['slide'] = $slide;
        }

        $out = $GLOBALS['TSFE']->cObj->cObjGetSingle('CONTENT', $conf);

        if($searchIndexWrap) {
            $out = "<!--TYPO3SEARCH_begin-->" . $out . "<!--TYPO3SEARCH_end-->";
        }
        return $out;
    }
}
