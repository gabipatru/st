function __(msg) {
	// load translations
	if ( typeof __.Translations == 'undefined' ) {
		__.Translations = getTranslations();
	}
	
	// get the translations
	if (msg in __.Translations) {
		return __.Translations[msg];
	}
	
	return msg;
}

function getTranslations() {
	Translations = getAllTranslations();
	return Translations[language];
}