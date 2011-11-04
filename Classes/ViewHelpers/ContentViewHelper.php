<?php

/**
	* Content view helper
	*
	* @package fluidpage
	* @subpackage 
	* @version
	*/
class Tx_Fluidpage_ViewHelpers_ContentViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	* Fetches content from a column, slides possible
	*
	* @param integer $colPos The colPos value in the db
	* @param integer $slide The value of the slide
	* @return String The content
	* @author Lucas Thurston
	*/
	public function render($colPos, $slide = 0) {
		$type = 'CONTENT';
		$conf = array(
			'table' => 'tt_content',
			'select.' => array(
				'orderBy' => 'sorting',
				'where' => 'colPos='.$colPos,
				'languageField' => 'sys_language_uid'
			)
		);
		if($slide) {
			$conf['slide'] = $slide;
		}
		return $GLOBALS['TSFE']->cObj->cObjGetSingle($type,$conf);
	}
}

?>