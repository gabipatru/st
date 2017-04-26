<?php

function filter($mVar, $sFilterType) {
	
	// initialize the filters array
	if (strstr($sFilterType, '|')) {
		$aFilters = explode('|', $sFilterType);
	}
	else {
		$aFilters = array($sFilterType);
	}

	// process the filters
	foreach ($aFilters as $filter) {
		// check if there are options
		if (strstr($filter, ']')) {
			$aOpt = explode('[', $filter);
			$filter = $aOpt[0];
			$aOpt = explode(']', $aOpt[1]);
			$option = $aOpt[0];
		}

		switch ($filter) {
			case 'bool': 
				if (!$mVar || $mVar == 'false') {
					$mVar = false;
				}
				$mVar = true;
				break;
			case 'int':
				if (is_string($mVar)) {
					$mVar = filter_var($mVar, FILTER_SANITIZE_NUMBER_INT);
				}
				$mVar = (int)$mVar;
				break;
			case 'float':
				/*if (is_string($mVar)) {
					$mVar = filter_var($mVar, FILTER_SANITIZE_NUMBER_FLOAT);
				}*/
				$mVar = (float)$mVar;
				break;
			case 'string':
				$mVar = (string)$mVar;
				break;
			case 'urlencode':
				$mVar = urlencode($mVar);
				break;
			case 'urldecode':
				$mVar = urldecode($mVar);
				break;
			case 'clean_html':
				$mVar = htmlspecialchars($mVar);
				break;
			case 'min':
				if ($mVar < $option) {
					$mVar = $option;
				}
				break;
			case 'max':
				if ($mVar > $option) {
					$mVar = $option;
				}
				break;
			case 'interval':
				$aInterval = explode('-', $option);
				if ($mVar < $aInterval[0]) {
					$mVar = $aInterval[0];
				}
				if ($mVar > $aInterval[1]) {
					$mVar = $aInterval[1];
				}
				break;
			case 'array':
				if (!is_array($mVar)) {
					$mVar = array();
				}
				break;
			case 'set':
				$aSet = explode(',', $option);
				if (is_array($aSet)) {
					foreach($aSet as $setValue) {
						if($setValue == $mVar) {
							break;
						}
					}
					$mVar = $aSet[0];
				}
				break;
			case 'email':
				$mVar = filter_var($mVar, FILTER_SANITIZE_EMAIL);
				break;
		}
	}
	return $mVar;
}

function filter_get($sIndex, $sFilterType) {
	if(!isset($_GET[$sIndex])) {
		$_GET[$sIndex] = '';
	}
	return filter($_GET[$sIndex], $sFilterType);
}

function filter_post($sIndex, $sFilterType) {
	if(!isset($_POST[$sIndex])) {
		$_POST[$sIndex] = '';
	}
	return filter($_POST[$sIndex], $sFilterType);
}

function filter_request($sIndex, $sFilterType) {
	if(!isset($_REQUEST[$sIndex])) {
		$_REQUEST[$sIndex] = '';
	}
	return filter($_REQUEST[$sIndex], $sFilterType);
}
?>