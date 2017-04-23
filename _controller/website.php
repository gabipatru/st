<?php
class controller_website {
	
	function _prehook() {
		mvc::setDecorations('website');
		
		mvc::addCSS('/bundle-admin.css');
		mvc::addJS('/bundle-admin.js');
	}
	
	function _posthook() {
	
	}
	
	###############################################################################
	## THE HOMEPAGE
	###############################################################################
	function homepage() {

	}

}
