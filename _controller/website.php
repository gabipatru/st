<?php
class controller_website {
	
	function _prehook() {
		mvc::setDecorations('website');
		
		mvc::addCSS('/bundle.css');
		mvc::addJS('/bundle.js');
		
		$oTranslations = Translations::getSingleton();
		$oTranslations->setModule('website');
	}
	
	function _posthook() {
	
	}
	
	###############################################################################
	## THE HOMEPAGE
	###############################################################################
	function homepage() {

	}
	
	###############################################################################
	## CONTACT PAGE
	###############################################################################
    function contact() {
        $messageSent = true;
        
        mvc::assign('messageSent', $messageSent);
    }
}
