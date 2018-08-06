<?php
require_once(TRAITS_DIR .'/Singleton.trait.php');
require_once(CLASSES_DIR .'/SetterGetter.php');

class mvc extends SetterGetter 
{
    use Singleton;
    
    private $View;
    
    protected $controllerprefix = 'controller_';
    
    protected $controllerfile;
    protected $controllerclass;
    protected $controllerfunction;
    
    /*
    * This function handles autoloading of classes
    * It is initialized from run()
    */
    public static function autoload($sClassName) {
        // some special cases
        if ($sClassName == 'DbData') {
            require_once(CLASSES_DIR . '/db_data/_db_data.php');
            return;
        }
        if ($sClassName == 'dbDataModel') {
            require_once(CLASSES_DIR . '/db_data/_db_data_model.php');
            return;
        }
        
        // normal autoload
        if (file_exists(CLASSES_DIR . '/' . $sClassName . '.php')) {
            require_once(CLASSES_DIR . '/' . $sClassName . '.php');
            return;
        }
        if (file_exists(CLASSES_DIR . '/db_data/' . $sClassName . '.php')) {
            require_once(CLASSES_DIR . '/db_data/' . $sClassName . '.php');
            return;
        }
        if (file_exists(TRAITS_DIR . '/' . $sClassName . '.trait.php')) {
            require_once(TRAITS_DIR . '/' . $sClassName . '.trait.php');
            return;
        }
        if (file_exists(CLASSES_DIR . '/controller_model/' . $sClassName . '.php')) {
            require_once(CLASSES_DIR . '/controller_model/' . $sClassName . '.php');
            return;
        }
    }
    
    
    ###############################################################################
    ## SET FUNCTIONS
    ###############################################################################
    
    /*
     * This function sets the controller class
     */
    public function setControllerClass($sClassName) {
        $this->controllerclass = $this->getControllerPrefix(). $sClassName;
    }
    
    /*
     * This function sets the controller file
     */
    public function setControllerFile($sFileName) {
        $this->controllerfile = CONTROLLER_DIR . '/' . $sFileName . '.php';
    }
    
    protected function serverSelf()
    {
        return $_SERVER['PHP_SELF'];
    }
    
    ###############################################################################
    ## SPECIAL FUNCTIONS
    ###############################################################################
    
    /*
     * This function extracts the controller class and function from the url and
     */
    private function extract() {
        $self = $this->serverSelf();
        
        $aSelf = explode('index.php', $self);
        if (empty($aSelf[1])) {
            return ['website', 'website', 'homepage'];
        }
        $aSelf = explode('?', $aSelf[1]);
        if (!$aSelf[0]) {
            return ['website', 'website', 'homepage'];
        }
        
        // remove any ../ from the path
        $aSelf[0] = str_replace('../', '', $aSelf[0]);

        $aControllerFunction = array_reverse(explode('/', $aSelf[0]));
        
        $sFunction = $aControllerFunction[0];
        unset($aControllerFunction[0]);
        
        $aControllerFunction = array_reverse($aControllerFunction);
        if (!$aControllerFunction[0]) {
            unset ($aControllerFunction[0]);
        }
        $sController = implode('_', $aControllerFunction);
        $sControllerPath = implode('/', $aControllerFunction);
        
        return array($sController, $sControllerPath, $sFunction);
    }
    
    /*
     * This function renders the view
     */
    private function view() {
        $this->View->render();
    }
    
    ###############################################################################
    ## MAIN FUNCTION
    ###############################################################################
    
    /*
     * This functions runs the controller and the view
     */
    public function run() {
        $timeStart = microtime(true);
        
        //register the autoloading class
        spl_autoload_register('mvc::autoload');
        
        // init the View
        $this->View = View::getSingleton();

        //extract controller class and function
        list($sClass, $sPath, $sFunction) = $this->extract();
        
        $this->setControllerFile($sPath);
        $this->setControllerClass($sClass);
        $this->setControllerFunction($sFunction);
        $this->View->setViewFile(VIEW_DIR .'/'. $sPath .'/'. $sFunction . '.php');
        $this->View->setViewDir($sPath);

        define('MVC_MODULE', $sPath);
        define('MVC_ACTION', $sFunction);
        define('MVC_MODULE_URL', HTTP_MAIN . '/' . MVC_MODULE);
        define('MVC_ACTION_URL', MVC_MODULE_URL . '/' . MVC_ACTION . '.html');
        
        // load the precontroller
        require_once(CONTROLLER_DIR.'/_precontroller.php');

        // load controller
        require_once($this->getControllerFile());
        
        // call the controller prehook, main function, posthook
        $sClassName = $this->getControllerClass();
        $oController = new $sClassName();
        
        // call prehook
        if (method_exists($sClassName, '_prehook')) {
            call_user_func(array($oController, '_prehook'));            
        }
        
        // call the main function
        if (method_exists($sClassName, $sFunction)) {
            call_user_func(array($oController, $sFunction));
        }
        else {
            die("Wrong file!");
        }
        
        // call posthook
        if (method_exists($sClassName, '_posthook')) {
            call_user_func(array($oController, '_posthook'));
        }
        
        // load the postcontroller
        require_once(CONTROLLER_DIR.'/_postcontroller.php');
        
        // load the preview
        require_once(CONTROLLER_DIR.'/_preview.php');

        // render the view
        $this->view();
        
        // load the post view
        require_once(CONTROLLER_DIR.'/_postview.php');
    }
}