<?php

/**
 * Use this trait if the class needs to filter variables
 */
trait Filter 
{   
    /**
     * The main filter function.
     * This will filter any variable type
     */
    public function filter($mVar, string $sFilterType = null)
    {
        if (!$sFilterType) {
            return $mVar;
        }
        // initialize the filters array
        if (strstr($sFilterType, '|') !== false) {
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
            
            // main filtering
            switch ($filter) {
                case 'bool': 
                    if (!$mVar || $mVar === 'false') {
                        $mVar = false;
                    }
                    else {
                        $mVar = true;
                    }
                    break;
                case 'int':
                    if (is_string($mVar)) {
                        $mVar = filter_var($mVar, FILTER_SANITIZE_NUMBER_INT);
                    }
                    $mVar = (int)$mVar;
                    break;
                case 'float':
                    if (is_string($mVar)) {
                        $mVar = filter_var($mVar, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    }
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
                    if ($mVar > $option || !$mVar) {
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
                        $mVar = [];
                    }
                    break;
                case 'set':
                    $aSet = explode(',', $option);
                    if (!is_array($aSet) || !in_array($mVar, $aSet)) {
                        $mVar = false;
                    }
                    break;
                case 'email':
                    $mVar = filter_var($mVar, FILTER_SANITIZE_EMAIL);
                    break;
            }
        }
        
        return $mVar;
    }
    
    ###############################################################################
    ## SOME HELPERS
    ###############################################################################
    public function filterBool($var) 
    {
        return $this->filter($var, 'bool');
    }
    
    public function filterInt($var) :int
    {
        return $this->filter($var, 'int');
    }
    
    public function filterFloat($var) :float 
    {
        return $this->filter($var, 'float');
    }
    
    public function filterString($var) :string 
    {
        return $this->filter($var, 'string');
    }
    
    public function filterMin($var, $minValue) 
    {
        $filter = "min[$minValue]";
        return $this->filter($var, $filter);
    }
    
    public function filterBefore($date, $minDate)
    {
        $date = strtotime($date);
        $minDate = strtotime($minDate);
        
        $filteredDate = $this->filterMin($date, $minDate);
        
        return date('Y-m-d H:i:s', $filteredDate);
    }
    
    public function filterMax($var, $maxValue) 
    {
        $filter = "max[$maxValue]";
        return $this->filter($var, $filter);
    }
    
    public function filterAfter($date, $maxDate)
    {
        $date = strtotime($date);
        $maxDate = strtotime($maxDate);
        
        $filteredDate = $this->filterMax($date, $maxDate);
        
        return date('Y-m-d H:i:s', $filteredDate);
    }
    
    public function filterInterval($var, $minValue, $maxValue) 
    {
        $filter = "interval[$minValue-$maxValue]";
        return $this->filter($var, $filter);
    }
    
    public function filterDateBetween($date, $minDate, $maxDate)
    {
        $date = strtotime($date);
        $minDate = strtotime($minDate);
        $maxDate = strtotime($maxDate);
        
        $filteredDate = $this->filterInterval($date, $minDate, $maxDate);
        
        return date('Y-m-d H:i:s', $filteredDate);
    }
    
    public function filterArray($var) :array 
    {
        return $this->filter($var, 'array');
    }
    
    public function filterSetOfValues($var, array $set) 
    {
        $filter = 'set['. implode(',', $set) .']';
        return $this->filter($var, $filter);
    }
    
    public function filterEmail($var)
    {
        return $this->filter($var, 'email');
    }
    
    ###############################################################################
    ## FUNCTIONS FOR GET, POST data
    ###############################################################################
    public function filterGET(string $sIndex, string $sFilterType = null) 
    {
        return $this->filter($_GET[$sIndex] ?? '', $sFilterType);
    }
    
    public function filterPOST(string $sIndex, string $sFilterType = null) 
    {
        return $this->filter($_POST[$sIndex] ?? '', $sFilterType);
    }
    
    public function filterREQUEST(string $sIndex, string $sFilterType = null)
    {
        return $this->filter($_REQUEST[$sIndex] ?? '', $sFilterType);
    }
    
    /**
     * Validate a value against a filter
     */
    private function validate($mVar, string $sFilterType) :bool
    {
        // initialize the filters array
        if (strstr($sFilterType, '|') !== false) {
            $aFilters = explode('|', $sFilterType);
        }
        else {
            $aFilters = array($sFilterType);
        }
        
        // process the filters
        foreach ($aFilters as $filter) {
            // main check
            switch ($filter) {
                case 'isEmail':
                    if (filter_var($mVar, FILTER_VALIDATE_EMAIL) === false) {
                        return false;
                    }
                    break;
                case 'isDomain':
                    if (filter_var($mVar, FILTER_VALIDATE_DOMAIN) === false || !$mVar) {
                        return false;
                    }
                    break;
                case 'isIp':
                    if (filter_var($mVar, FILTER_VALIDATE_IP) === false) {
                        return false;
                    }
                    break;
                case 'isMac':
                    if (filter_var($mVar, FILTER_VALIDATE_MAC) === false) {
                        return false;
                    }
                    break;
                case 'isUrl':
                    if (filter_var($mVar, FILTER_VALIDATE_URL) === false) {
                        return false;
                    }
                    break;
                case 'isUnsigned':
                    if (ctype_digit((string) $mVar) === false) {
                        return false;
                    }
                    break;
            }
        }
        
        // if none of the filters turn out to be false, return true
        return true;
    }
    
    ###############################################################################
    ## FUNCTIONS FOR FILTERING data
    ###############################################################################
    public function isEmail($value) :bool
    {
        return $this->validate($value, 'isEmail');
    }
    
    public function isDomain($value) :bool
    {
        return $this->validate($value, 'isDomain');
    }
    
    public function isIp($value) :bool
    {
        return $this->validate($value, 'isIp');
    }
    
    public function isMac($value) :bool
    {
        return $this->validate($value, 'isMac');
    }

    public function isUrl($value) :bool
    {
        return $this->validate($value, 'isUrl');
    }
    
    public function isUnsigned($value) :bool
    {
        return $this->validate($value, 'isUnsigned');
    }
}