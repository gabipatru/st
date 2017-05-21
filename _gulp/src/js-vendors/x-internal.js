/*
 * This class is used for ajax requests 
 * Can create, hide it's own spinner on a given element
 * Can lock the ajax so the user won't spam with requests
 * Can evaluate json responses
 */
var JQ_AJAX = {
		
	// the type of response: html, json
	dataType: false,
	
	// if set to true, the user won't be able to spam with requests
	lock: false,
	
	inProgress: false,
	
	// the POST URL
	url: false,
	
	// in which element do we display the spinner
	spinIn: false,
	
	// in which element do we display the HTML response
	element: false,
	
	// the parameters to send
	params: false,
	
	// code to be executed on JSON error
	onError: function() {
		return true;
	},
	// code to be executed on JSON success
	onSuccess: function() {
		return true;
	},
	
	// callse onError, onSuccess or appends HTML
	onFinish: function(data) {
		JQ_AJAX.inProgress = false;
		
		// hide spinner
		if (JQ_AJAX.spinIn) {
			$('#' + JQ_AJAX.spinIn).find('img.js-spinnerImg').hide();
		}
		
		// process response
		if (JQ_AJAX.dataType == "json") {
			if (data['response'] == "success" && typeof(JQ_AJAX.onSuccess) == "function") {
				JQ_AJAX.onSuccess();
			}
			if (data['response'] == "error" && typeof(JQ_AJAX.onError) == "function") {
				JQ_AJAX.onError();
			}
		}
		if (JQ_AJAX.dataType == "html" && JQ_AJAX.element) {
			$('#' + JQ_AJAX.element).html(data);
		}
		
		return true;
	},
	
	// runs the ajax
	fetch: function(options) {
		JQ_AJAX.spinIn = false;
		
		// make some checks
		if (!options.dataType) {
			JQ_AJAX.dataType = 'text';
		}
		else {
			JQ_AJAX.dataType = options.dataType;
		}
		
		// if no URL is given return 
		if (!options.url) {
			console.debug('AJAX error: no url set');
			return false;
		}
		JQ_AJAX.url = options.url;
		if (options.lock) {
			JQ_AJAX.lock = true;
		}
		else {
			JQ_AJAX.lock = false;
		}
		if (options.spinIn) {
			JQ_AJAX.spinIn = options.spinIn;
		}
		if (options.params) {
			JQ_AJAX.params = options.params;
		}
		
		if (options.element) {
			JQ_AJAX.element = options.element;
		}
		
		// don't allow multiple instances if lock options is set to true
		if (JQ_AJAX.lock && JQ_AJAX.inProgress) {
			return true;
		}
		
		JQ_AJAX.inProgress = true;
		
		// create / show spinner
		if (JQ_AJAX.spinIn) {
			if ($('#' + JQ_AJAX.spinIn).find('img.js-spinnerImg').length) {
				$('#' + JQ_AJAX.spinIn).find('img.js-spinnerImg').show();
			}
			else {
				var spinnerImg = $('<img class="js-spinnerImg" src="'+HTTP_IMAGES + '/common/spinner.gif'+'" />');
				$('#' + JQ_AJAX.spinIn).append(spinnerImg);
			}
		}
		
		// hook onSuccess and onError if request is json type
		if (JQ_AJAX.dataType == "json" && typeof(options.onSuccess) == "function") {
			JQ_AJAX.onSuccess = options.onSuccess;
		}
		if (JQ_AJAX.dataType == "json" && typeof(options.onError) == "function") {
			JQ_AJAX.onError = options.onError;
		}
		
		// run the ajax
		$.ajax({
			type: "POST",
			url: JQ_AJAX.url,
			dataType: JQ_AJAX.dataType,
			data: JQ_AJAX.params
		}).done(JQ_AJAX.onFinish);
	},
	
	END: null
};