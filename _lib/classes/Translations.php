<?php

/*
 * Translate texts in any language.
 * This class is a singleton
 */

class Translations {
    
    private static $instance = null;
    private $language;
    private $module;
    
    private $translations = array();
    
    protected function __construct() {
    
    }
    
    private function __clone() {
    
    }
    
    private function __wakeup() {
    
    }
    
    public static function getSingleton() {
        if (!static::$instance) {
            static::$instance = new static;
        }
        
        return static::$instance;
    }
    
    public function setModule($module, $load = true) {
        if (!$module) {
            return false;
        }
        
        $this->module = $module;
        
        if ($load) {
            $this->loadTranslations();
        }
    }
    
    public function getModule() {
        if (empty($this->module)) {
            return DEFAULT_TRANSLATION_MODULE;
        }
        
        return $this->module;
    }
    
    public function setLanguage($lang) {
        if (!$lang) {
            return false;
        }
        
        $this->language = $lang;
    }
    
    public function getLanguage() {
        if (empty($this->language)) {
            return DEFAULT_TRANSLATION_LANGUAGE;
        }
        
        return $this->language;
    }
    
    /*
     * Load a translation. Language and module must be set
     */
    public function loadTranslations() {
        $language   = $this->getLanguage();
        $module     = $this->getModule();
        
        // load, process translation
        if (file_exists(TRANSLATIONS_DIR . '/' . $language . '/' . $module . '.csv')) {
            $fis = fopen(TRANSLATIONS_DIR . '/' . $language . '/' . $module . '.csv', "r");
            while($line = fgetcsv($fis)) {
                $this->translations['untranslated'][] = $line[0];
                $this->translations['translated'][] = $line[1];
            }
        }
        else {
            log_message('translation.log', 'Warning! Translation file ' . $language . '/' . $module . '.csv' . ' Does not exist!');
        }
    }
    
    /*
     * Translate a simple message
     */
    public function __($msg) {
        $key = array_search($msg, $this->translations['untranslated']);
        if ($key !== false) {
            return $this->translations['translated'][$key];
        }
        
        return $msg;
    }
    
    /*
     * Translate a message with format
     * Uses vsprintf
     */
    public function ___() {
        $numargs = func_num_args();
        if ($numargs < 1) {
            return '';
        }
        
        // The message to be translated
        $msg = func_get_arg(0);
        
        $key = array_search($msg, $this->translations['untranslated']);
        if ($key !== false) {
            return $msg;
        }
        
        $args = array();
        for ($i=1; $i<$numargs; $i++) {
            $args[] = func_get_arg($i);
        }
        
        return vsprintf($this->translations['translated'][$key], $args);
    }
}