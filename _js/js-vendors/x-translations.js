function __(msg) {
	// get the translations
	if (msg in Translations[LANGUAGE]) {
		return Translations[LANGUAGE][msg];
	}
	
	return msg;
}