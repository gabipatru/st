<?php

/*
 * This class requires a function to submit it's changes on selects
 * It displays one or more select based on the config array
 */

class GridFilters {
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
		$sRet .= '<option value="">-- '.__('All').' --</option>';
		
		foreach ($this->aConfig[$sField]['valid_values'] as $key => $option) {
			$sValueName = $aValues[$key];
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
	function GFHref($page = false, $search = false, $sort = false) {
		$str = '';
		foreach ($this->aConfig as $sField => $aData) {
		    if ($this->$sField !== false) {
			    $str .= '&'.$sField.'='.$this->$sField;
		    }
		}
		
		if ($page == true && !empty($_GET['page'])) {
		    $str .= '&page=' . $_GET['page'];
		}
		if ($search == true && !empty($_GET['search'])) {
		    $str .= '&search=' . $_GET['search'];
		}
		if ($sort == true && !empty($_GET['sort']) && !empty($_GET['sort_crit'])) {
		    $str .= '&sort=' . $_GET['sort'] . '&sort_crit=' . $_GET['sort_crit'];
		}
		
		return $str;
	}
	
	// generate the fields to be used in db model
	function filters() {
	    $aFilters = array();
	    foreach ($this->aConfig as $sField => $aData) {
	        if ($this->$sField !== false) {
	            $aFilters[$sField] = $this->$sField;
	        }
	    }
	    return $aFilters;
	}
	
	// generate the sort params
	function sortParams($sortField, $sortCrit) {
	    if (empty($sortField)) {
	        return '';
	    }
	    
	    return '&sort='. $sortField .'&sort_crit='. ($sortCrit == 'asc' ? 'desc' : 'asc');
	}
}
