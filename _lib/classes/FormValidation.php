<?php 
class FormValidation {
	private $_aFormConfig;
	private $_bValid;
	public $_js_code;
	
	function __construct($aFormConfig) {
		$this->_aFormConfig = $aFormConfig;
		
		// initialize javascript validation
		$this->_js_code = '<script type="text/javascript" nonce="29af2i">';
		$this->_js_code .= '
		    var ValidateSubmit = {
		        isSubmitted: false,
		        submit: function() {
	                if (ValidateSubmit.isSubmitted) {
			            return false;
		            }
		            ValidateSubmit.isSubmitted = true;
		            $("input[type=submit]").attr("disabled", "disabled");
		            form.submit();
	            },
		        END: null
	        };
		';
		$this->_js_code .= 'var aFormValidate = Array();';
		// you need an object ValidateSubmit.submit() to handle submits
		$this->_js_code .= 'aFormValidate["submitHandler"] = ValidateSubmit.submit;';
		$this->_js_code .= 'aFormValidate["rules"] = Array();';
		$this->_js_code .= 'aFormValidate["messages"] = Array();';
		foreach ($this->_aFormConfig['rules'] as $sField => $mRule) {
		    if (empty($mRule)) {
		        continue;
		    }
			if (!is_array($mRule)) {
				$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"] = "'.$mRule.'";';
			}
			else {
				$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"] = Array();';
				foreach ($mRule as $sRule => $sOption) {
					if (is_numeric($sOption)) {
						$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"]["'.$sRule.'"] = '.$sOption.';';
					}
					elseif ($sOption === true) {
						$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"]["'.$sRule.'"] = true;';
					}
					elseif ($sOption === false) {
						$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"]["'.$sRule.'"] = false;';
					}
					else {
						$this->_js_code .= 'aFormValidate["rules"]["'.$sField.'"]["'.$sRule.'"] = "#'.$sOption.'";';
					}
				}
			}
		}
		foreach ($this->_aFormConfig['messages'] as $sField => $sMessage) {
			$this->_js_code .= 'aFormValidate["messages"]["'.$sField.'"] = "'.$sMessage.'";';
		}
		$this->_js_code .= '</script>';
	}
	
	private function validate_field($sField, $sRule, $mOption = false) {
		$sFieldError = $sField . '_error';
		$this->$sField = (isset($_POST[$sField]) ? htmlspecialchars($_POST[$sField]) : '');

		// check if the page is POSTED
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			$this->_bValid = false;
			$this->$sFieldError = '';
			return false;
		}
		
		// validate the field
		if (empty($this->$sFieldError)) {
			$this->$sFieldError = '';
		}
		switch ($sRule) {
			case 'required':
				if ($mOption === true && strlen($this->$sField) == 0) {
					// in this case we also check if it's not in $_FILES
					if (empty($_FILES[$sField])) {
						$this->_bValid = false;
						$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
					}
				}
				break;
			case 'minlength':
				if (strlen($this->$sField) < $mOption) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'maxlength':
				if (strlen($this->$sField) > $mOption) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'min':
				if ($this->$sField < $mOption) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'max':
				if ($this->$sField > $mOption) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'email':
				if (!filter_var($this->$sField, FILTER_VALIDATE_EMAIL)) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'url':
				if (!filter_var($this->$sField, FILTER_VALIDATE_URL)) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
            case 'digits':
                if (!is_numeric($this->$sField)) {
                    $this->_bValid = false;
                    $this->$sFieldError = $this->_aFormConfig['messages'][$sField];
                }
                break;
			case 'number':
				if (!filter_var($this->$sField, FILTER_VALIDATE_FLOAT)) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
			case 'equalTo':
				if ($this->$sField != $_POST[$mOption]) {
					$this->_bValid = false;
					$this->$sFieldError = $this->_aFormConfig['messages'][$sField];
				}
				break;
		}
	} 
	
	public function validate() {
		$this->_bValid = true;
		foreach ($this->_aFormConfig['rules'] as $sField => $mRule) {
			if (is_array($mRule)) {
				foreach ($mRule as $sRule => $mOption) {
					$this->validate_field($sField, $sRule, $mOption);
				}
			}
			else {
				$sRule = $mRule;
				$this->validate_field($sField, $sRule, true);
			}
		}

		return $this->_bValid;
	}
	
	public function reset() {
		foreach ($this->_aFormConfig['rules'] as $sField => $mRule) {
			$this->$sField = '';
			$sFieldError = $sField . '_error';
			$this->$sFieldError = '';
		}
	}
}