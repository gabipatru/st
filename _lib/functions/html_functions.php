<?php

function select_date($oFV = null, $iEnd = 0, $iStart = 0) {
	
	if (!$iStart) {
		$iStart = (int) date('Y');
	}
	if (!$iEnd) {
		$iEnd = 1950;
	}
	
	// the day
	$sHtml = '<select name="date_select_day" id="date_select_day" >';
	$sHtml .= '<option value="">-- zi --</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '01' ? 'selected="SELECTED"' : '').' value="01">01</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '02' ? 'selected="SELECTED"' : '').' value="02">02</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '03' ? 'selected="SELECTED"' : '').' value="03">03</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '04' ? 'selected="SELECTED"' : '').' value="04">04</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '05' ? 'selected="SELECTED"' : '').' value="05">05</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '06' ? 'selected="SELECTED"' : '').' value="06">06</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '07' ? 'selected="SELECTED"' : '').' value="07">07</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '08' ? 'selected="SELECTED"' : '').' value="08">08</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '09' ? 'selected="SELECTED"' : '').' value="09">09</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '10' ? 'selected="SELECTED"' : '').' value="10">10</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '11' ? 'selected="SELECTED"' : '').' value="11">11</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '12' ? 'selected="SELECTED"' : '').' value="12">12</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '13' ? 'selected="SELECTED"' : '').' value="13">13</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '14' ? 'selected="SELECTED"' : '').' value="14">14</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '15' ? 'selected="SELECTED"' : '').' value="15">15</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '16' ? 'selected="SELECTED"' : '').' value="16">16</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '17' ? 'selected="SELECTED"' : '').' value="17">17</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '18' ? 'selected="SELECTED"' : '').' value="18">18</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '19' ? 'selected="SELECTED"' : '').' value="19">19</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '20' ? 'selected="SELECTED"' : '').' value="20">20</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '21' ? 'selected="SELECTED"' : '').' value="21">21</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '22' ? 'selected="SELECTED"' : '').' value="22">22</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '23' ? 'selected="SELECTED"' : '').' value="23">23</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '24' ? 'selected="SELECTED"' : '').' value="24">24</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '25' ? 'selected="SELECTED"' : '').' value="25">25</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '26' ? 'selected="SELECTED"' : '').' value="26">26</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '27' ? 'selected="SELECTED"' : '').' value="27">27</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '28' ? 'selected="SELECTED"' : '').' value="28">28</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '29' ? 'selected="SELECTED"' : '').' value="29">29</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '30' ? 'selected="SELECTED"' : '').' value="30">30</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_day) && $oFV->date_select_day == '31' ? 'selected="SELECTED"' : '').' value="31">31</option>';
	$sHtml .= '</select>';
	
	// the month
	$sHtml .= '<select name="date_select_month" id="date_select_month">';
	$sHtml .= '<option value="">-- luna --</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '01' ? 'selected="SELECTED"' : '').' value="01">Ianuarie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '02' ? 'selected="SELECTED"' : '').' value="02">Februarie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '03' ? 'selected="SELECTED"' : '').' value="03">Martie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '04' ? 'selected="SELECTED"' : '').' value="04">Aprilie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '05' ? 'selected="SELECTED"' : '').' value="05">Mai</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '06' ? 'selected="SELECTED"' : '').' value="06">Iunie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '07' ? 'selected="SELECTED"' : '').' value="07">Iulie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '08' ? 'selected="SELECTED"' : '').' value="08">August</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '09' ? 'selected="SELECTED"' : '').' value="09">Septembrie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '10' ? 'selected="SELECTED"' : '').' value="10">Octobrie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '11' ? 'selected="SELECTED"' : '').' value="11">Noembrie</option>';
	$sHtml .= '<option '.(isset($oFV->date_select_month) && $oFV->date_select_month == '12' ? 'selected="SELECTED"' : '').' value="12">Decembrie</option>';
	$sHtml .= '</select>';
	
	// the year
	$sHtml .= '<select name="date_select_year" id="date_select_year">';
	$sHtml .= '<option value="">-- an --</option>';
	for($i=$iStart; $i>= $iEnd; $i--) {
		$sHtml .= '<option '.(isset($oFV->date_select_year) && $oFV->date_select_year == $i ? 'selected="SELECTED"' : '').' value="'.$i.'">'.$i.'</option>';
	}
	$sHtml .= '</select>';
	
	return $sHtml;
}

function display_countries($selected = '') {
    echo '
<select name="country">
<option value="">-- Alege --</option>
<option value="Romania">Romania</option>
<option value="Afganistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Territory">British Indian Ocean Ter</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Canary Islands">Canary Islands</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Channel Islands">Channel Islands</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Island">Cocos Island</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote DIvoire">Cote DIvoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curaco">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Ter">French Southern Ter</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Great Britain">Great Britain</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Hawaii">Hawaii</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea North">Korea North</option>
<option value="Korea Sout">Korea South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malaysia">Malaysia</option>
<option value="Malawi">Malawi</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Midway Islands">Midway Islands</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Nambia">Nambia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherland Antilles">Netherland Antilles</option>
<option value="Netherlands">Netherlands</option>
<option value="Nevis">Nevis</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau Island">Palau Island</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Phillipines">Philippines</option>
<option value="Pitcairn Island">Pitcairn Island</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Republic of Montenegro">Republic of Montenegro</option>
<option value="Republic of Serbia">Republic of Serbia</option>
<option value="Reunion">Reunion</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="St Lucia">St Lucia</option>
<option value="Samoa">Samoa</option>
<option value="Samoa American">Samoa American</option>
<option value="San Marino">San Marino</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tahiti">Tahiti</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad &amp; Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks and Caicos Islands">Turks &amp; Caicos Is</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Erimates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States of America">United States of America</option>
<option value="Uraguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City State">Vatican City State</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
<option value="Wake Island">Wake Island</option>
<option value="Yemen">Yemen</option>
<option value="Zaire">Zaire</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>
    ';
}

function display_bytes($iBytes) {
	$temp = $iBytes;
	$sBytes = $iBytes . ' B';
	if ($temp > 1024) {
		$temp = $temp / 1024;
		$sBytes = sprintf('%10.2f', $temp);
		$sBytes  = $sBytes . ' KB';
		$temp = (int) $temp;
	}
	if ($temp > 1024) {
		$temp = $temp / 1024;
		$sBytes = sprintf('%10.2f', $temp);
		$sBytes = $sBytes . ' MB';
		$temp = (int) $temp;
	}
	return $sBytes;
}

?>