<?php
class mvc {
    static private $View;
    
	static private $sControllerPrefix = 'controller_';
	
	static private $aSkip = array(
			'JS'               => false,
			'CSS'              => false,
	        'JS_BUNDLE'        => false,
	        'CSS_BUNDLE'       => false,
			'META'             => false,
			'HEADER'           => false,
			'FOOTER'           => false,
			'VISIBLE_HEADER'   => false,
			'VISIBLE_FOOTER'   => false
		);
	
	static private $sControllerFile;
	static private $sControllerClass;
	static private $sControllerFunction;
	
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
	public static function setControllerClass($sClassName) {
		self::$sControllerClass = self::$sControllerPrefix . $sClassName;
	}
	
	/*
	 * This function set the controller function to be called
	 */
	public static function setControllerFunction($sFunctionName) {
		self::$sControllerFunction = $sFunctionName;
	}
	
	/*
	 * This function sets the controller file
	 */
	public static function setControllerFile($sFileName) {
		self::$sControllerFile = CONTROLLER_DIR . '/' . $sFileName . '.php';
	}
	
	/*
	 * This function sets the page title, description and keywords
	 */
	public static function addSEOParams($sTitle, $sDescription, $sKeywords) {
		self::$sPageTitle = $sTitle;
		mvc::addMETA('description', $sDescription);
		mvc::addMETA('keywords', $sKeywords);
	}
	
	/*
	 * This function will skip both css and js bundles
	 */
	public static function skipBundles($bSkip = true) {
	    self::skipCSSBundle($bSkip);
	    self::skipJSBundle($bSkip);
	}
	
	/*
	 * This function skips everything
	*/
	public static function skipAll($bSkip = true) {
		self::skipHeader($bSkip);
		self::skipFooter($bSkip);
		self::skipVisibleHeader($bSkip);
		self::skipVisibleFooter($bSkip);
	}
	
	###############################################################################
	## GET FUNCTIONS
	###############################################################################
	
	/*
	 * This function gets the controller class
	 */
	public static function getControllerClass() {
		return self::$sControllerClass;
	}
	
	/*
	 * This function get the controller function to be called
	 */
	public static function getControllerFunction() {
		return self::$sControllerFunction;
	}
	
	/*
	 * This function gets the controller pathname
	 */
	public static function getControllerFile() {
		return self::$sControllerFile;
	}
	
	
	###############################################################################
	## SPECIAL FUNCTIONS
	###############################################################################
	
	/*
	 * This function extracts the controller class and function from the url and
	 */
	private static function extract() {
		$self = $_SERVER['PHP_SELF'];

		$aSelf = explode('index.php', $self);
		if (!$aSelf[1]) {
			return array('homepage', 'main');
		}
		$aSelf = explode('?', $aSelf[1]);
		if (!$aSelf[0]) {
			return array('homepage', 'main');
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
	private static function view() {
		self::$View->render();
	}
	
	###############################################################################
	## MAIN FUNCTION
	###############################################################################
	
	/*
	 * This functions runs the controller and the view
	 */
	public static function run() {
	    $timeStart = microtime(true);
	    
		//register the autoloading class
		spl_autoload_register('mvc::autoload');
		
		// init the View
		self::$View = View::getSingleton();

		//extract controller class and function
		list($sClass, $sPath, $sFunction) = self::extract();
		
		mvc::setControllerFile($sPath);
		mvc::setControllerClass($sClass);
		mvc::setControllerFunction($sFunction);
		self::$View->setViewFile(VIEW_DIR .'/'. $sPath .'/'. $sFunction . '.php');
		self::$View->setViewDir($sPath);

		define('MVC_MODULE', $sPath);
		define('MVC_ACTION', $sFunction);
		define('MVC_MODULE_URL', HTTP_MAIN . '/' . MVC_MODULE);
		define('MVC_ACTION_URL', MVC_MODULE_URL . '/' . MVC_ACTION . '.html');
		
		// load the precontroller
		require_once(CONTROLLER_DIR.'/_precontroller.php');

		// load controller
		require_once(mvc::getControllerFile());
		
		// call the controller prehook, main function, posthook
		$sClassName = mvc::getControllerClass();
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
		self::view();
		
		// load the post view
		require_once(CONTROLLER_DIR.'/_postview.php');
	}
}