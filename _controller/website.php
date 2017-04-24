<?php
class controller_website {
	
	function _prehook() {
		mvc::setDecorations('website');
		
		mvc::addCSS('/bundle.css');
		mvc::addJS('/bundle.js');
	}
	
	function _posthook() {
	
	}
	
	###############################################################################
	## THE HOMEPAGE
	###############################################################################
	function homepage() {

	}

}
