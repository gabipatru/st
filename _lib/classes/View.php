<?php

/*
 * This is the View part of MVC
 */
class View extends SetterGetter
{
    use Singleton;
    use Translation;
    use Html;
    
    private $aVarAssigned = [];
    private $aVarAssignedRef = [];
    
    private $aCacheBuster = [];
    private $aMeta = [];
    private $aCSS = [];
    private $aJS = [];
    
    ###############################################################################
    ## CACHE BUSTER
    ###############################################################################
    public function addCacheBuster(array $cacheBuster)
    {
        $this->aCacheBuster = $cacheBuster;
    }
    
    public function getCacheBuster() :array
    {
        return $this->aCacheBuster;
    }
    
    /*
     * This function adds a CSS file to be loaded
     * If the css file name matches a cachebuster key, the the css file is indexed by the cachebuster fingerprint
     */
    public function addCSS(string $sCssFileName)
    {
        $aCacheBuster = $this->getCacheBuster();
        if (is_array($aCacheBuster)) {
            $arrayKeys = array_keys($aCacheBuster);
            foreach ($arrayKeys as $key) {
                if (strstr($key, $sCssFileName) !== false) {
                    $this->aCSS[$aCacheBuster[$key]] = $sCssFileName;
                    return;
                }
            }
        }
        $this->aCSS[] = $sCssFileName;
    }
    
    public function getCSS() :array
    {
        return $this->aCSS;
    }
    
    /*
     * This function adds a JS file to be loaded
     * If the js file name matches a cachebuster key, the the css file is indexed by the cachebuster fingerprint
     */
    public function addJS(string $sJsFileName)
    {
        $aCacheBuster = $this->getCacheBuster();
        if (is_array($aCacheBuster)) {
            $arrayKeys = array_keys($aCacheBuster);
            foreach ($arrayKeys as $key) {
                if (strstr($key, $sJsFileName) !== false) {
                    $this->aJS[$aCacheBuster[$key]] = $sJsFileName;
                    return;
                }
            }
        }
        $this->aJS[] = $sJsFileName;
    }
    
    public function getJS() :array
    {
        return $this->aJS;
    }
    
    public function addMeta(string $sName, string $sContent)
    {
        $this->aMeta[$sName] = $sContent;
    }
    
    public function getMeta() :array
    {
        return $this->aMeta;
    }
    
    public function addSEOParams(string $title, string $description, string $keywords)
    {
        $this->setPageTitle($title);
        $this->addMeta('description', $description);
        $this->addMeta('keywords', $keywords);
    }
    
    /*
     * This function will allow you to skip CSS,JS, Headers and everything else
     */
    public function skipAll()
    {
        $this->setSkipJs(true);
        $this->setSkipCss(true);
        $this->setSkipHeader(true);
        $this->setSkipFooter(true);
    }
    
    ###############################################################################
    ## ASSIGN FUNCTION
    ###############################################################################
    public function assign(string $index, $mVar) 
    {
        $this->aVarAssigned[$index] = $mVar;
    }
    
    public function assign_by_ref(string $index, &$mVar) 
    {
        $this->aVarAssignedRef[$index] =& $mVar;
    }
    
    /*
     * The escape assign clears html special characters from strings and arrays
     */
    public function assignEscape(string $index, $mVar)
    {
        if (is_array($mVar)) {
            $this->aVarAssigned[$index] = $this->escape_recursive($mVar);
        }
        else {
            $this->aVarAssigned[$index] = htmlspecialchars($mVar);
        }
    }
    
    private function escape_recursive(array &$arr)
    {
        if (is_array($arr)) {
            foreach($arr as $key => $value) {
                if (is_array($arr[$key])) {
                    $arr[$key] = $this->escape_recursive($arr[$key]);
                }
                else {
                    $arr[$key] = htmlspecialchars($arr[$key]);
                }
            }
        }
        return $arr;
    }
    
    public function getAssignedVar(string $index)
    {
        if (isset($this->aVarAssigned[$index])) {
            return $this->aVarAssigned[$index];
        }
        
        return null;
    }
    
    public function getAssignedByRefVar(string $index)
    {
        if (isset($this->aVarAssignedRef[$index])) {
            return $this->aVarAssignedRef[$index];
        }
        
        return null;
    }
    
    ###############################################################################
    ## MAIN FUNCTION
    ###############################################################################
    public function render()
    {
        // register CSS and JS files
        $this->assign('_aJS', $this->aJS);
        $this->assign('_aCSS', $this->aCSS);
        $this->assign('_aMETA', $this->aMeta);

        // assign the current view directory
        $this->assign('_CURRENT_VIEW_DIR', $this->getViewDir());
        
        // register all the assigned values
        extract($this->aVarAssigned);
        extract($this->aVarAssignedRef, EXTR_REFS);
        
        // load the header
        require_once(VIEW_DIR.'/_core/header.php');
        
        // load the decorations header
        require_once(DECORATIONS_DIR . '/' . $this->getDecorations() . '/header.php');
        
        // load the common part of the section
        if (file_exists($this->getViewDir() . '/_common.php')) {
            require_once($this->getViewDir() . '/_common.php');
        }
        
        // load the view file
        if (!file_exists($this->getViewFile())) {
            die("View file ".$this->getViewFile()." not found. Please create it.");
        }
        require_once($this->getViewFile());
        
        // load the decorations footer
        require_once(DECORATIONS_DIR . '/' . $this->getDecorations() . '/footer.php');
        
        // load the footer
        require_once(VIEW_DIR.'/_core/footer.php');   
    }
}