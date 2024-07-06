<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';
include 'config.php';

$databases = [
    "kindergarten_database" => "Kindergarten",
    "lower_primary_database" => "Lower Primary",
    "upper_primary_database" => "Upper Primary",
    "secondary_database" => "Secondary",
    "science_secondary_database" => "Science Secondary"
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>ELM SCHOOLS STUDENT REGISTRATION FORM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentNumber = 2187;

        function loadCurrentNumber() {
            const savedNumber = localStorage.getItem('currentNumber');
            currentNumber = savedNumber !== null ? parseInt(savedNumber, 10) : 1;
        }

        function saveCurrentNumber() {
            localStorage.setItem('currentNumber', currentNumber);
        }

        function setStartingPoint() {
            const startNumber = parseInt(document.getElementById('startNumber').value, 10);
            if (!isNaN(startNumber)) {
                currentNumber = startNumber;
                saveCurrentNumber();
            } else {
                console.error('Invalid starting number');
            }
        }

        function generateUsername() {
            const database = document.getElementById('database').value;
            const usernameField = document.getElementById('username');
            const prefixMap = {
                "kindergarten_database": "ELM_KG",
                "lower_primary_database": "ELM_LP",
                "upper_primary_database": "ELM_UP",
                "secondary_database": "ELM_SEC",
                "science_secondary_database": "ELM_SS"
            };
            const prefix = prefixMap[database];
            usernameField.value = `${prefix}${currentNumber}`;
            currentNumber++;
            saveCurrentNumber();
        }

        function fetchGroups(database) {
            $.ajax({
                url: 'fetchGroups.php',
                type: 'POST',
                data: { database: database },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                        return;
                    }

                    const yearGroupSelect = $('#YearGroup');
                    yearGroupSelect.empty();
                    response.yearGroups.forEach(function(group) {
                        yearGroupSelect.append(new Option(group.name, group.gibbonYearGroupID));
                    });

                    const formGroupSelect = $('#FormGroup');
                    formGroupSelect.empty();
                    response.formGroups.forEach(function(group) {
                        formGroupSelect.append(new Option(group.name, group.gibbonFormGroupID));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        $(document).ready(function() {
            $('#database').change(function() {
                const database = $(this).val();
                fetchGroups(database);
            });

            loadCurrentNumber();
        });
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a href="index.php" class="btn btn-primary">Register New Student</a>
        <a href="reports.php" class="btn btn-info">Reports</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </li>
  </div>
</nav>
    <div class="container py-5">
        <form action="studentRegistration.php" method="post" id="registrationForm">
            <div class="card card-registration" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="p-5">
                                <h3 class="fw-normal mb-5" style="color: #4835d4;">Student Information</h3>
                                <div class="mb-4 pb-2">
                                    <label for="database">Select School Section:</label>
                                    <select name="database" id="database" class="form-select">
                                        <option value="" disabled selected>Select Section</option>
                                        <?php foreach ($databases as $key => $value): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="YearGroup">Select Year Group:</label>
                                    <select class="form-select" id="YearGroup" name="YearGroup"></select>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="FormGroup">Select Form Group:</label>
                                    <select class="form-select" id="FormGroup" name="FormGroup"></select>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="dateStart">Start Date:</label>
                                    <input type="date" class="form-control" id="dateStart" name="dateStart">
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="secondName">Second Name:</label>
                                    <input type="text" class="form-control" id="secondName" name="secondName" required>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="surname">Last Name:</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="mb-4 pb-2">
                                    <button type="button" class="btn btn-primary" onclick="generateUsername()">Generate Username</button>
                                    <input type="text" class="form-control mt-2" id="username" name="username" readonly>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="startNumber">Starting Number:</label>
                                    <input type="number" class="form-control" id="startNumber" name="startNumber">
                                    <button type="button" class="btn btn-secondary mt-2" onclick="setStartingPoint()">Set Starting Point</button>
                                </div>

                                <div class="mb-4 pb-2">
                                    <label for="gender">Gender:</label>
                                    <div>
                                        <input type="radio" id="male" name="gender" value="M" required> Male
                                        <input type="radio" id="female" name="gender" value="F"> Female
                                    </div>
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="dob">Date of Birth:</label>
                                    <input type="date" class="form-control" id="dob" name="dob">
                                </div>
                                <div class="mb-4 pb-2">
                                    <label for="countryOfBirth">Country of Birth:</label>
                                    <select class="form-select" id="countryOfBirth" name="countryOfBirth">
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
                        <div class="col-lg-6 bg-indigo text-white">
                            <div class="p-5">
                                <div class="mb-4 pb-2">
                                    <h3 class="fw-normal">Family Details</h3>
                                    <div class="form-outline form-white">
                          <label class="form-label" for="form3Examplea2">Home Address *Unit, Building, Street</label>
                          <select class="select" id="address1" name="address1" required>
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
                                <div>
      <h4>Parent / Guardian Infomation</h4>
    </div>
    <div>
      <h4>Father</h4>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev2" name="f_fname" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev2">First Name</label>
        </div>

      </div>
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev3" name="f_lname" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev3">Second name</label>
        </div>
      </div>
    </div>
    <div class="mb-4 pb-2">
      <div class="form-outline">
        <input type="text" id="form3Examplev4" name="f_phone" class="form-control form-control-lg" />
        <label class="form-label" for="form3Examplev4">Phone Number</label>
      </div>
    </div>
    <div>
      <h4>Mother</h4>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev2" name="m_fname" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev2">First Name</label>
        </div>

      </div>
      <div class="col-md-6 mb-4 pb-2">

        <div class="form-outline">
          <input type="text" id="form3Examplev3" name="m_lname" class="form-control form-control-lg" />
          <label class="form-label" for="form3Examplev3">Second name</label>
        </div>
      </div>
    </div>
    <div class="mb-4 pb-2">
      <div class="form-outline">
        <input type="text" id="form3Examplev4" name="m_phone" class="form-control form-control-lg" />
        <label class="form-label" for="form3Examplev4">Phone Number</label>
      </div>
    </div>
                      <div class="mb-4">
                        <div class="form-outline form-white">
                          <input type="email" id="form3Examplea9" name="parent_email" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Examplea9">Parent/Guardian Email</label>
                        </div>
                      </div>

                                <div class="mb-4 pb-2">
                                    <label for="specialNeeds">Special Needs:</label>
                                    <textarea class="form-control" id="specialNeeds" name="specialNeeds" rows="4"></textarea>
                                </div>
                                <div class="form-check d-flex justify-content-start mb-4 pb-3">
                        <input class="form-check-input me-3" type="checkbox" name="cookieConsent" id="form2Example3c" />
                        <label class="form-check-label text-white" for="form2Example3">
                          I do accept the <a href="#!" class="text-white"><u>Terms and Conditions</u></a> of your
                          site.
                        </label>
                      </div>

                                <div class="mb-4 pb-2">
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </div>
                                <div class="mb-4 pb-2">
                                    <h5>Notes:</h5>
                                    <p>Please make sure all required fields are filled out. For any queries, contact the administration office.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
