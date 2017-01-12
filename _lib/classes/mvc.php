<?php
class mvc {
	static private $sControllerPrefix = 'controller_';
	
	static private $aVarAssigned = array();
	static private $aVarAssignedRef = array();
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
	static private $aCSS = array();
	static private $aJS = array();
	static private $aMETA = array();
	
	static private $sPageTitle = '';
	static private $sPageDecription = '';
	static private $sPageKeywords = '';
	
	static private $sControllerFile;
	static private $sControllerClass;
	static private $sControllerFunction;
	static private $sViewDir;
	static private $sViewFile;
	static private $sDecorations = 'default';
	
	/*
	 * This function handles autoloading of classes
	* It is initialized from run()
	*/
	public static function autoload($sClassName) {
		if (file_exists(CLASSES_DIR . '/' . $sClassName . '.php')) {
			require_once(CLASSES_DIR . '/' . $sClassName . '.php');
			return;
		}
		if (file_exists(CLASSES_DIR . '/db_data/' . $sClassName . '.php')) {
			require_once(CLASSES_DIR . '/db_data/' . $sClassName . '.php');
			return;
		}
		if ($sClassName == 'dbDataModel') {
			require_once(CLASSES_DIR . '/db_data/_db_data_model.php');
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
	 * This function sets the view file
	 */
	public static function setViewFile($sFileName) {
		self::$sViewFile = VIEW_DIR . '/' . $sFileName . '.php';
	}
	
	/*
	 * This function sets the view directory
	 */
	public static function setViewDir($sFolderName) {
		self::$sViewDir = VIEW_DIR . '/' . $sFolderName;
	}
	
	/*
	 * This function sets the decorations
	 */
	public static function setDecorations($sFolderName) {
		self::$sDecorations = $sFolderName;
	}
	
	/*
	 * This function adds a CSS file to be loaded
	 */
	public static function addCSS($sCssFileName) {
		self::$aCSS[] = $sCssFileName;
	}
	
	/*
	 * This function adds a JS file to be loaded
	 */
	public static function addJS($sJsFileName) {
		self::$aJS[] = $sJsFileName;
	}
	
	/*
	 * This function sets a meta tag to be defined in the head
	 */
	public static function addMETA($sName, $sContent) {
		self::$aMETA[$sName] = $sContent;
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
	 * This function sets a skip parameter.
	 * There skip parameters are assigned to the view as $_SKIP_[name]
	 */
	private static function setSkip($index, $value) {
		self::$aSkip[$index] = $value;
	}
	
	/*
	 * This function skips META declarations
	 */
	public static function skipMETA($bSkip = true) {
		self::setSkip('META', $bSkip);
	}
	
	/*
	 * This function skipe JS includes
	 */
	public static function skipJS($bSkip = true) {
		self::setSkip('JS', $bSkip);
	}
	

	/*
	 * This function will skip the main js bundle
	 */
	public static function skipJSBundle($bSkip = true) {
	    self::setSkip('JS_BUNDLE', $bSkip);
	}
	
	/*
	 * This function skips CSS includes
	 */
	public static function skipCSS($bSkip = true) {
		self::setSkip('CSS', $bSkip);
	}
	
	/*
	 * This function will skip the main css bundle
	 */
	public static function skipCSSBundle($bSkip = true) {
	    self::setSkip('CSS_BUNDLE', $bSkip);
	}
	
	/*
	 * This function skips the header
	*/
	public static function skipHeader($bSkip = true) {
		self::setSkip('HEADER', $bSkip);
	}
	
	/*
	 * This function skips the footer
	*/
	public static function skipFooter($bSkip = true) {
		self::setSkip('FOOTER', $bSkip);
	}
	
	/*
	 * This function skips the visible header
	*/
	public static function skipVisibleHeader($bSkip = true) {
		self::setSkip('VISIBLE_HEADER', $bSkip);
	}
	
	/*
	 * This function skips the visible footer
	*/
	public static function skipVisibleFooter($bSkip = true) {
		self::setSkip('VISIBLE_FOOTER', $bSkip);
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
	
	/*
	 * This function gets the view pathname
	 */
	public static function getViewFile() {
		return self::$sViewFile;
	}
	
	/*
	 * This function gets the view directory
	 */
	public static function getViewDir() {
		return self::$sViewDir;
	}
	
	/*
	 * This function returns the decorations directory
	 */
	public static function getDecorations() {
		return self::$sDecorations;
	}
	
	###############################################################################
	## ASSIGN FUNCTION
	###############################################################################
	
	/*
	 * Simple assign
	 */
	public static function assign($index, $mVar) {
		self::$aVarAssigned[$index] = $mVar;
	}
	
	/*
	 * Assign by reference
	 */
	public static function assign_by_ref($index, &$mVar) {
		self::$aVarAssignedRef[$index] =& $mVar;
	}
	
	/*
	 * The escape assign clears html special characters from strings and arrays
	 */
	private static function escape_recursive(&$arr) {
		if (is_array($arr)) {
			foreach($arr as $key => $value) {
				if (is_array($arr[$key])) {
					$arr[$key] = self::escape_recursive($arr[$key]);
				}
				else {
					$arr[$key] = htmlspecialchars($arr[$key]);
				}
			}
		}
		return $arr;
	} 
	
	public static function assign_escape($index, $mVar) {
		if (is_scalar($mVar)) {
			self::$aVarAssigned[$index] = htmlspecialchars($mVar);
		}
		if (is_array($mVar)) {
			self::$aVarAssigned[$index] = self::escape_recursive($mVar);
		}
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
		// add decorations css
		if (file_exists(CSS_DIR.'/'.mvc::getDecorations().'/style.css')) {
			mvc::addCSS('/'.mvc::getDecorations().'/style.css');
		}
		
		// register CSS and JS files
		mvc::assign('_aJS', self::$aJS);
		mvc::assign('_aCSS', self::$aCSS);
		mvc::assign('_aMETA', self::$aMETA);
		
		// register the Skips
		if (is_array(self::$aSkip)) {
			foreach (self::$aSkip as $key => $value) {
				mvc::assign('_SKIP_'.$key, $value);
			}
		}
		
		// assign the current view directory
		mvc::assign('_CURRENT_VIEW_DIR', mvc::getViewDir());
		
		// assign page title, description, keywords
		mvc::assign('_PAGE_TITLE', self::$sPageTitle);
		
		// register all the assigned values
		extract(mvc::$aVarAssigned);
		extract(mvc::$aVarAssignedRef, EXTR_REFS);
		
		// load the header
		require_once(VIEW_DIR.'/_core/header.php');
		
		// load the decorations header
		require_once(DECORATIONS_DIR . '/' . mvc::getDecorations() . '/header.php');
		
		// load the common part of the section
		if (file_exists(mvc::getViewDir() . '/_common.php')) {
			require_once(mvc::getViewDir() . '/_common.php');
		}
		
		// load the view file
		if (!file_exists(mvc::getViewFile())) {
			die("View file ".mvc::getViewFile()." not found. Please create it.");
		}
		require_once(mvc::getViewFile());
		
		// load the decorations footer
		require_once(DECORATIONS_DIR . '/' . mvc::getDecorations() . '/footer.php');
		
		// load the footer
		require_once(VIEW_DIR.'/_core/footer.php');
	}
	
	###############################################################################
	## MAIN FUNCTION
	###############################################################################
	
	/*
	 * This functions runs the controller and the view
	 */
	public static function run() {
		//register the autoloading class
		spl_autoload_register('mvc::autoload');
		
		//extract controller class and function
		list($sClass, $sPath, $sFunction) = self::extract();
		
		mvc::setControllerFile($sPath);
		mvc::setControllerClass($sClass);
		mvc::setControllerFunction($sFunction);
		mvc::setViewFile($sPath . '/' . $sFunction);
		mvc::setViewDir($sPath);

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