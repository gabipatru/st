<?php

/*
 * This class requires a function to submit it's changes on selects
 * It displays one or more select based on the config array
 */

class grid_filters {
	private $aConfig;
	
	function __construct($aGF) {
		if (!$aGF) {
			return false;
		}
		
		$this->aConfig = $aGF;
		
		// set up the values
		foreach ($aGF as $sFieldName => $aData) {
			if (!is_array($this->aConfig[$sFieldName]['valid_values'])) {
				continue;
			}
			if (!empty($_REQUEST[$sFieldName]) && in_array($_REQUEST[$sFieldName], $this->aConfig[$sFieldName]['valid_values'])) {
				$this->$sFieldName = $_REQUEST[$sFieldName];
			}
			else {
				$this->$sFieldName = $this->aConfig[$sFieldName]['default'];
			}
		}
	}
	
	// Display a <select>
	function GFSelect($sField, $aValues = array()) {
		if (!$sField) {
			return false;
		}
		if (!isset($this->$sField)) {
			return false;
		}
		if (!$aValues) {
			$aValues = $this->aConfig[$sField]['valid_values'];
		}
		
		// HTML for select
		$sRet = '<select name="'.$sField.'" id="'.$sField.'" class="js-gfselect">';
		foreach ($this->aConfig[$sField]['valid_values'] as $option) {
			$aVal = each($aValues);
			$sValueName = $aVal['value'];
			$sel = "";
			if ($option == $this->$sField) {
				$sel = 'selected="SELECTED"';
			}
			$sRet .= '<option '.$sel.' value="'.$option.'">'.$sValueName.'</option>';
		}
		$sRet .= '</select>';
		return $sRet;
	}
	
	// fetch a string of params to be used on a GET URL
	function GFHref() {
		$str = '';
		foreach ($this->aConfig as $sField => $aData) {
			$str .= '&'.$sField.'='.$this->$sField;
		}
		return $str;
	}
}
