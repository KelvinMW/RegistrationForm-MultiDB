<?php
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
    <div class="container py-5">
        <h1 class="text-center mb-4">ELM Schools Student Registration Form</h1>
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
                                        <option value="Tanzania">Tanzania</option>
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
