<?php
// Database connection details
include 'config.php';
$databases = [
    "kindergarten_database" => "elm_kg",
    "lower_primary_database" => "elm_lower-p",
    "upper_primary_database" => "elm_upper-p",
    "secondary_database" => "elm_secondary",
    "science_secondary_database" => "elm_science_secondary"
];
// Check if a database is selected
if (isset($_POST['database']) && array_key_exists($_POST['database'], $databases)) {
    $dbname = $databases[$_POST['database']];
} else {
  $dbname = 'elm_secondary';
}
// Establish database connection
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch current school year ID
$currentSchoolYearQuery = "SELECT gibbonSchoolYearID FROM gibbonSchoolYear WHERE status = 'Current'";
$currentSchoolYearResult = $conn->query($currentSchoolYearQuery);
if ($currentSchoolYearResult->num_rows > 0) {
    $currentSchoolYearRow = $currentSchoolYearResult->fetch_assoc();
    $currentSchoolYearID = $currentSchoolYearRow['gibbonSchoolYearID'];
} else {
    die("No current school year found.");
}
// Fetch year groups for the current school year
$yearGroupQuery = "SELECT gibbonYearGroupID, name FROM gibbonYearGroup";
$yearGroupResult = $conn->query($yearGroupQuery);
// Fetch form groups for the current school year
$formGroupQuery = "SELECT gibbonFormGroupID, name FROM gibbonFormGroup WHERE gibbonSchoolYearID = $currentSchoolYearID";
$formGroupResult = $conn->query($formGroupQuery);
$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ELM SCHOOLS STUDENT REGISTRATION FORM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1 class="text-center">ELM SCHOOLS STUDENT REGISTRATION FORM</h1>
  <form action="studentRegistration.php" method="post" id="registrationForm">
    <section class="h-100 h-custom gradient-custom-2">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12">
              <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                <div class="card-body p-0">
                  <div class="row g-0">
                    <div class="col-lg-6">
                      <div class="p-5">
                        <h3 class="fw-normal mb-5" style="color: #4835d4;">Student Information</h3>
                        <div id="StudentInformation">
                          <div class="mb-4 pb-2">
                          <div class="mb-4 pb-2">
            <label for="database">Select Database:</label>
            <select name="database" id="database" onchange="this.form.submit()">
                <option value="" disabled selected>Select a database</option>
                <?php
                foreach ($databases as $key => $value) {
                    echo "<option value='$key'>$key</option>";
                }
                ?>
            </select>
        </div>
    <?php if (isset($yearGroupResult) && isset($formGroupResult)): ?>

            <div class="mb-4 pb-2">
                <label for="YearGroup">Select Year Group:</label>
                <select class="select" id="YearGroup" name="YearGroup">
                    <?php
                    while ($yearGroupRow = $yearGroupResult->fetch_assoc()) {
                        echo "<option value='" . $yearGroupRow['gibbonYearGroupID'] . "'>" . $yearGroupRow['name'] . "</option>";
                    }
                    ?>
                </select>
                  </br>
                <label for="FormGroup">Select Form Group:</label>
                <select class="select" id="FormGroup" name="FormGroup">
                    <?php
                    while ($formGroupRow = $formGroupResult->fetch_assoc()) {
                        echo "<option value='" . $formGroupRow['gibbonFormGroupID'] . "'>" . $formGroupRow['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
    <?php endif; ?>
                          </div>
                          <label for="dateStart">Start Date</label>
                          <input type="date" value="dateStart" id="dateStart" name="dateStart">
        <div id="StudentInformation">
          <div class="row">
            <div class="col-md-6 mb-4 pb-2">
              <div class="form-outline">
                <input type="text" id="firstName" name="firstName" class="form-control form-control-lg" />
                <label class="form-label" for="firstName" required>First name</label>
              </div>
            </div>
            <div class="col-md-6 mb-4 pb-2">

              <div class="form-outline">
                <input type="text" id="secondName" name="secondName" class="form-control form-control-lg" />
            <label class="form-label" for="secondName" required>Second name</label>
              </div>
            </div>
          </div>
          <div class="mb-4 pb-2">
            <div class="form-outline">
              <input type="text" id="surname" name="surname" class="form-control form-control-lg" />
              <label class="form-label" for="surname" required>Last Name</label>
            </div>
          </div>
        </div>
        <button type="button" onclick="generateUsername()">Generate Username</button>
        <input type="text" id="username" name="username" readonly>
        <div class="mb-4 pb-2">
        <label for="gender">Gender:</label>
        <input type="radio" id="male" name="gender" value="M" required> Male
        <input type="radio" id="female" name="gender" value="F"> Female<br><br>
        <div class="row">
          <div class="col-md-6 mb-4 pb-2">
            <div class="form-outline">
              <label class="form-label" for="form3Examplev2">Date of Birth</label>
              <input type="date" id="dob" name="dob" class="form-control form-control-lg" />
            </div>
            <div class="form-label">
              <label class="form-label" for="form3Examplea3">Country of Birth</label>
              <select id="countryOfBirth" name="countryOfBirth">
              <option value="Afghanistan">Afghanistan</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antartica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
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
            <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
            <option value="Brunei Darussalam">Brunei Darussalam</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Cocos Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo">Congo</option>
            <option value="Congo">Congo, the Democratic Republic of the</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Cota D'Ivoire">Cote d'Ivoire</option>
            <option value="Croatia">Croatia (Hrvatska)</option>
            <option value="Cuba">Cuba</option>
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
            <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="France Metropolitan">France, Metropolitan</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="French Southern Territories">French Southern Territories</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guam">Guam</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
            <option value="Holy See">Holy See (Vatican City State)</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran (Islamic Republic of)</option>
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
            <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
            <option value="Korea">Korea, Republic of</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Lao">Lao People's Democratic Republic</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macau">Macau</option>
            <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia, Federated States of</option>
            <option value="Moldova">Moldova, Republic of</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn">Pitcairn</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Puerto Rico">Puerto Rico</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russian Federation</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint LUCIA">Saint LUCIA</option>
            <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia (Slovak Republic)</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somaliland" selected>Somaliland</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
            <option value="Span">Spain</option>
            <option value="SriLanka">Sri Lanka</option>
            <option value="St. Helena">St. Helena</option>
            <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syrian Arab Republic</option>
            <option value="Taiwan">Taiwan, Province of China</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania, United Republic of</option>
            <option value="Thailand">Thailand</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Viet Nam</option>
            <option value="Virgin Islands (British)">Virgin Islands (British)</option>
            <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
            <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Yemen">Yemen</option>
            <option value="Serbia">Serbia</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
            </select>
            </div>
            </div>
            </div>
          <h3 class="fw-normal mb-5" style="color: #4835d4;">Educational Background</h3>
          </div>
                          <div class="mb-4 pb-2">
                          </div>
                          <label for="previous_Year">Year in Previous School:</label>
                          <select id="previous_Year" name="previous_Year">
                            <option value="PlayGroup">PlayGroup</option>
                            <option value="KG1">KG1</option>
                            <option value="KG2">KG2</option>
                            <option value="KG3">KG3</option>
                            <option value="Year 1">Year 1</option>
                            <option value="Year 2">Year 2</option>
                            <option value="Year 3">Year 3</option>
                            <option value="Year 4">Year 4</option>
                            <option value="Year 5">Year 5</option>
                            <option value="Year 6">Year 6</option>
                            <option value="Year 7">Year 7</option>
                            <option value="Year 8">Year 8</option>
                            <option value="Year 9">Year 9</option>
                            <option value="Year 10">Year 10</option>
                            <option value="Year 11">Year 11</option>
                            <option value="A Level">A level</option>
                            </select><br><br>
                          <label for="lastSchool">Name of Previous School:</label>
                          <input type="text" id="lastSchool" name="lastSchool" required><br><br>
                          <label for="departureReason">Reason for Transfer:</label>
                          <textarea id="departureReason" name="departureReason" required></textarea><br><br>
                          <h3 class="fw-normal mb-5" style="color: #4835d4;">Student Medical Information</h3>
                          <div class="mb-4 pb-2">
                            <div class="form-outline">
                              <label class="form-label" for="form3Examplea2">Any relevant medical information the school needs to know about:</label>
                              <textarea class="form-control" id="form3Examplea2" rows="4"></textarea>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 bg-indigo text-white">
                      <div class="p-5">
                        <h3 class="fw-normal mb-5">Family Details</h3>
                      <div class="mb-4 pb-2">
                        <div class="form-outline form-white">
                          <label class="form-label" for="form3Examplea2">Home Address *Unit, Building, Street</label>
                          <select class="select" id="address1">
                            <option value="Ayaxa">Ayaxa</option>
                            <option value="Bada Cas">Bada Cas</option>
                            <option value="Biyo dhacay">Biyo dhacay</option>
                            <option value="Boqol iyo Kontonka">Boqol iyo Kontonka</option>
                            <option value="Caabayee">Caabayee</option>
                            <option value="Calamadaha">Calamadaha</option>
                            <option value="Cayngal">Cayngal</option>
                            <option value="Daami">Daami</option>
                            <option value="Dunbuluq">Dunbuluq</option>
                            <option value="Ganad">Ganad</option>
                            <option value="Gol-Jano">Gol-Jano</option>
                            <option value="Guul-Alle">Guul-Alle</option>
                            <option value="Idaacada">Idaacada</option>
                            <option value="Isha borama">Isha borama</option>
                            <option value="Jameeco Weyn">Jameeco Weyn</option>
                            <option value="Jig Jiga-Yar">Jig Jiga-Yar</option>
                            <option value="Lixle">Lixle</option>
                            <option value="Maxamed Mooge">Maxamed Mooge</option>
                            <option value="New Hargeisa">New Hargeisa</option>
                            <option value="Qudhacdheer">Qudhacdheer</option>
                            <option value="Shacabka">Shacabka</option>
                            <option value="Sheikh Madar">Sheikh Madar</option>
                            <option value="Sheikh Nuur">Sheikh Nuur</option>
                            <option value="Waraaba Salaan">Waraaba Salaan</option>
                            <option value="Xawaadle">Xawaadle</option>
                            <option value="Xeero Awr">Xeero Awr</option>
                          </select>
                          </div>
                      </div>
                      <div class="mb-4 pb-2">
                        <div class="form-outline form-white">

                        </div>
                      </div>
    <div>
      <h4>Parent / Guardian Infomation</h4>
    </div>
    <div>
      <h4>Father</h4>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev2" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev2">First Name</label>
        </div>

      </div>
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev3" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev3">Second name</label>
        </div>
      </div>
    </div>
    <div class="mb-4 pb-2">
      <div class="form-outline">
        <input type="text" id="form3Examplev4" class="form-control form-control-lg" />
        <label class="form-label" for="form3Examplev4">Phone Number</label>
      </div>
    </div>
    <div>
      <h4>Mother</h4>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev2" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev2">First Name</label>
        </div>

      </div>
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev3" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev3">Second name</label>
        </div>
      </div>
    </div>
    <div class="mb-4 pb-2">
      <div class="form-outline">
        <input type="text" id="form3Examplev4" class="form-control form-control-lg" />
        <label class="form-label" for="form3Examplev4">Phone Number</label>
      </div>
    </div>
                      <div class="row">
                        <div class="col-md-5 mb-4 pb-2">

                          <div class="form-outline form-white">
                            <input type="text" id="form3Examplea7" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Examplea7">Code +252</label>
                          </div>

                        </div>
                        <div class="col-md-7 mb-4 pb-2">

                          <div class="form-outline form-white">
                            <input type="text" id="form3Examplea8" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Examplea8">Parent/Gardian Phone Number</label>
                          </div>

                        </div>
                      </div>

                      <div class="mb-4">
                        <div class="form-outline form-white">
                          <input type="text" id="form3Examplea9" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Examplea9">Parent/Guardian Email</label>
                        </div>
                      </div>

                      <div class="form-check d-flex justify-content-start mb-4 pb-3">
                        <input class="form-check-input me-3" type="checkbox" value="" id="form2Example3c" />
                        <label class="form-check-label text-white" for="form2Example3">
                          I do accept the <a href="#!" class="text-white"><u>Terms and Conditions</u></a> of your
                          site.
                        </label>
                      </div>

                      <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-light btn-lg btn-block">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  </form>
  <script>
    let currentNumber = 1;
    // Load the current number from localStorage
    function loadCurrentNumber() {
        const savedNumber = localStorage.getItem('currentNumber');
        if (savedNumber !== null) {
            currentNumber = parseInt(savedNumber, 10);
        } else {
            currentNumber = 1; // Default to 1 if not found
        }
    }
    // Save the current number to localStorage
    function saveCurrentNumber() {
        localStorage.setItem('currentNumber', currentNumber);
    }
    // Set the starting point and save it to localStorage
    function setStartingPoint() {
        const startNumber = parseInt(document.getElementById('startNumber').value, 10);
        if (!isNaN(startNumber)) {
            currentNumber = startNumber;
            saveCurrentNumber();
        } else {
            console.error('Invalid starting number');
        }
    }
    // Generate the username and increment the current number
    function generateUsername() {
        const database = document.getElementById('database').value;
        const usernameField = document.getElementById('username');
        const prefixMap = {
          "kindergarten_database": "KG",
          "lower_primary_database": "LP",
          "upper_primary_database": "UP",
          "secondary_database": "SEC",
          "science_secondary_database": "SS"
        };
        const prefix = prefixMap[database];
        const username = `${prefix}${currentNumber}`;
        usernameField.value = username;
        currentNumber++;
        saveCurrentNumber();
    }
    // Load the current number when the script is first run
    loadCurrentNumber();
</script>
</body>
</html>
