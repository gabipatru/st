<?php
/*
 * This file will create jsvascript translations 
 * based on php translations
 */

require_once(__DIR__ . '/_config.php');

class PHPtoJStranslations extends AbstractCron {
    
    const JS_TRANSLATION_FILE = JS_CODE_DIR. '/translations.js';
    
    private function deleteOldJStranslations() {
        if (file_exists(self::JS_TRANSLATION_FILE)) {
            unlink(self::JS_TRANSLATION_FILE);
            $this->displayMsg('Old translation file deleted');
        }
        else {
            $this->displayMsg('No translation file to delete');
        }
    }
    
    /**
     * Check if the translation files actually exist
     */
    private function checkTranslationFiles() {
        $languages = Translations::LANGUAGES;
        $translationModules = Translations::MODULES;
        
        foreach ($languages as $currentLang) {
            foreach ($translationModules as $currentModule) {
                if (!file_exists(TRANSLATIONS_DIR. '/' .$currentLang. '/' .$currentModule. '.csv')) {
                    $this->displayMsg("Translation file not found ! Module $currentModule language $currentLang");
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Transform php translations to js, return a prepared js string
     */
    public function phpTOjs() {
        $oTranslations = Translations::getSingleton();
        
        $languages = Translations::LANGUAGES;
        $translationModules = Translations::MODULES;
        
        // process translations for all langiages
        $allLangs = [];
        foreach ($languages as $currentLang) {
            $oTranslations->setLanguage($currentLang);
            
            // load all translations for given language
            foreach ($translationModules as $currentModule) {
                $oTranslations->setModule($currentModule, true);
            }
            
            // get all untranslated messages
            $allMessages = $oTranslations->getAllKeys();
            if (!$allMessages) {
                $this->displayMsg("There are no translations for language $currentLang");
                continue;
            }
            
            // form pairs of $untranslated:$translated messages
            $processed = [];
            foreach ($allMessages as $msg) {
                $msgTranslated = $oTranslations->__($msg);
                $processed[] = "'$msg':'$msgTranslated'";
            }
            
            // one single string with all $untranslated:$translated pairs
            $processed = implode(',', $processed);
            $processed = '{'.$processed.'}';
            
            // now we have the all translations for a language
            $processed = "'" .$currentLang. "':" .$processed;
            
            // save it to the array
            $allLangs[] = $processed;
            
            // finally reset translations for next language
            $oTranslations->resetTranslations();
            
            $this->displayMsg("All done for $currentLang");
        }
        
        $allLangs = implode(',', $allLangs);
        $allLangs = 'var Translations={' .$allLangs. '}';
        
        return $allLangs;
    }
    
    public function run() {
        $oTranslations = Translations::getSingleton();
        
        // we don't want the old translations
        $this->deleteOldJStranslations();
        
        $languages = Translations::LANGUAGES;
        $translationModules = Translations::MODULES;
        
        // check if all files exist
        if (! $this->checkTranslationFiles()) {
            return;
        }
        
        // do the translations
        $jsString = $this->phpTOjs();
        
        // save them to a file
        $r = file_put_contents(self::JS_TRANSLATION_FILE, $jsString);
        
        if ($r) {
            $this->displayMsg("New translations are saved. New size: $r");
        }
        else {
            $this->displayMsg("Could not save translations to HDD!");
        }
    }
}

$cron = new PHPtoJStranslations();
$cron->run();