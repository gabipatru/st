function __(msg) {
	
	// load translations
	static var Translations;
	if (!Translations) {
		Translations = getTranslations();
	}
	
	// get the translations
	if (msg in Translations) {
		return Translations[msg];
	}
	
	return msg;
}

function getTranslations() {
	Translations = getAllTranslations();
	return Translations[language];
}