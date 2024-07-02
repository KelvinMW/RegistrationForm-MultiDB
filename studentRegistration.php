<?php
include 'config.php';

// Debug the posted values
echo '<pre>';
print_r($_POST);
echo '</pre>';
//default values
$title= '';
$surname= '';
$firstName= '';
$preferredName= '';
$officialName= '';
$nameInCharacters= '';
$gender= 'Unspecified';
$username= '';
$passwordStrong= '';
$passwordStrongSalt= '';
$passwordForceReset= 'N';
$status= 'Full';
$canLogin= 'Y';
$gibbonRoleIDPrimary= 0;
$gibbonRoleIDAll= '';
$dob= null;
$email= null;
$emailAlternate= null;
$image_240= null;
$lastIPAddress= '';
$lastTimestamp= null;
$lastFailIPAddress= null;
$lastFailTimestamp= null;
$failCount= 0;
$address1= '';
$address1District= '';
$address1Country= '';
$address2= '';
$address2District= '';
$address2Country= '';
$phone1Type= '';
$phone1CountryCode= '';
$phone1= '';
$phone3Type= '';
$phone3CountryCode= '';
$phone3= '';
$phone2Type= '';
$phone2CountryCode= '';
$phone2= '';
$phone4Type= '';
$phone4CountryCode= '';
$phone4= '';
$website= '';
$languageFirst= '';
$languageSecond= '';
$languageThird= '';
$countryOfBirth= '';
$birthCertificateScan= '';
$ethnicity= '';
$religion= '';
$profession= '';
$employer= '';
$jobTitle= '';
$emergency1Name= '';
$emergency1Number1= '';
$emergency1Number2= '';
$emergency1Relationship= '';
$emergency2Name= '';
$emergency2Number1= '';
$emergency2Number2= '';
$emergency2Relationship= '';
$gibbonHouseID= null;
$studentID= '';
$dateStart= null;
$dateEnd= null;
$gibbonSchoolYearIDClassOf= null;
$lastSchool= '';
$nextSchool= '';
$departureReason= '';
$transport= '';
$transportNotes= '';
$calendarFeedPersonal= '';
$viewCalendarSchool= 'Y';
$viewCalendarPersonal= 'Y';
$viewCalendarSpaceBooking= 'N';
$gibbonApplicationFormID= null;
$lockerNumber= '';
$vehicleRegistration= '';
$personalBackground= '';
$messengerLastRead= null;
$privacy= null;
$dayType= null;
$gibbonThemeIDPersonal= null;
$gibboni18nIDPersonal= null;
$studentAgreements= null;
$googleAPIRefreshToken= '';
$microsoftAPIRefreshToken= '';
$genericAPIRefreshToken= '';
$receiveNotificationEmails= 'Y';
$mfaSecret= null;
$mfaToken= '';
$cookieConsent= null;
$fields= '';
// Receive values from the submitted form
$title = $_POST['title'] ?? '';
$dbname = $_POST['database'];
$surname = trim($_POST['surname'] ?? '');
$firstName = trim($_POST['firstName'] ?? '');
$secondName = trim($_POST['secondName'] ?? '');
$preferredName = $firstName . ' ' . $secondName;
$officialName = $firstName . ' ' . $secondName . ' ' . $surname;
$gender = $_POST['gender'] ?? '';
$username = trim($_POST['username'] ?? '');
$status = 'Full';
$canLogin = 'N';
$gibbonRoleIDPrimary = '003';
$dob = $_POST['dob'] ?? '';
$date_obj = new DateTime($dob);
$dob = $date_obj->format('Y-m-d');
$email = trim($_POST['email'] ?? '');
$emailAlternate = trim($_POST['emailAlternate'] ?? '');
$address1 = $_POST['address1'] ?? '';
$address1District = 'Hargeisa';
$address1Country = 'Somaliland';
$phone1 = preg_replace('/[^0-9+]/', '', $_POST['phone1'] ?? '');
$countryOfBirth = $_POST['countryOfBirth'] ?? '';
$emergency1Name = $_POST['emergency1Name'] ?? '';
$emergency1Number1 = $_POST['emergency1Number1'] ?? '';
$emergency1Number2 = $_POST['emergency1Number2'] ?? '';
$emergency1Relationship = $_POST['emergency1Relationship'] ?? '';
$emergency2Name = $_POST['emergency2Name'] ?? '';
$emergency2Number1 = $_POST['emergency2Number1'] ?? '';
$emergency2Number2 = $_POST['emergency2Number2'] ?? '';
$emergency2Relationship = $_POST['emergency2Relationship'] ?? '';
$studentID = $_POST['studentID'] ?? '';
$dateStart = $_POST['dateStart'] ?? '';
$date_obj = new DateTime($dateStart);
$dateStart = $date_obj->format('Y-m-d');
$lastSchool = $_POST['lastSchool'] ?? '';
$privacy = !empty($_POST['privacyOptions']) ? implode(',', $_POST['privacyOptions']) : null;
$studentAgreements = !empty($_POST['studentstudentAgreements']) ? implode(',', $_POST['studentstudentAgreements']) : null;
$dayType = $_POST['dayType'] ?? null;
$nameInCharacters = '';
$passwordStrong = '';
$passwordStrongSalt = '';
$gibbonRoleIDAll = '003';
$address2 = $_POST['address2'] ?? '';
$address2District = 'Hargeisa';
$address2Country = 'Somaliland';
$phone2 = preg_replace('/[^0-9+]/', '', $_POST['phone2'] ?? '');
$phone1CountryCode = $_POST['phone1CountryCode'] ?? '';
$phone2CountryCode = $_POST['phone2CountryCode'] ?? '';
$phone3CountryCode = $_POST['phone3CountryCode'] ?? '';
$phone3 = preg_replace('/[^0-9+]/', '', $_POST['phone3'] ?? '');
$phone4CountryCode = $_POST['phone4CountryCode'] ?? '';
$phone4 = preg_replace('/[^0-9+]/', '', $_POST['phone4'] ?? '');
$website = $_POST['website'] ?? '';
$languageFirst = $_POST['languageFirst'] ?? '';
$languageSecond = $_POST['languageSecond'] ?? '';
$languageThird = $_POST['languageThird'] ?? '';
// Use the correct database name
$databaseMap = [
    'kindergarten_database' => 'elm_kg',
    'lower_primary_database' => 'elm_lower-p',
    'upper_primary_database' => 'elm_upper-p',
    'secondary_database' => 'elm_secondary',
    'science_secondary_database' => 'elm_science-secondary',
];
$dbname = $databaseMap[$dbname] ?? '';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Debug connection values
echo '<br>debug:<br>';
echo 'Database: ' . $dbname . '<br>';
echo 'Host: ' . $host . '<br>';
echo 'User: ' . $user . '<br>';
echo 'Password: ' . $password . '<br>';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare insert statement
$sql = "INSERT INTO gibbonPerson (
    title,preferredName, officialName, gender, username, status, canLogin, gibbonRoleIDPrimary, dob, email, emailAlternate,
    address1, address1District, address1Country, phone1, countryOfBirth, emergency1Name, emergency1Number1, emergency1Number2,
    emergency1Relationship, emergency2Name, emergency2Number1, emergency2Number2, emergency2Relationship, studentID, dateStart,
    lastSchool, privacy, studentAgreements, dayType, nameInCharacters, passwordStrong, passwordStrongSalt, gibbonRoleIDAll, address2, address2District, address2Country, phone2
    , phone1CountryCode, phone2CountryCode, phone3CountryCode, phone3, phone4CountryCode, phone4, website, languageFirst, languageSecond, languageThird,
    gibbonApplicationFormID, lockerNumber, vehicleRegistration, personalBackground, messengerLastRead, gibbonThemeIDPersonal, gibboni18nIDPersonal
    , googleAPIRefreshToken, microsoftAPIRefreshToken, genericAPIRefreshToken, receiveNotificationEmails, mfaSecret, mfaToken, cookieConsent,
    image_240, lastIPAddress, lastTimestamp, lastFailIPAddress, gibbonSchoolYearIDClassOf, birthCertificateScan,
     ethnicity, religion, profession, employer, jobTitle, nextSchool, departureReason, transport, transportNotes, calendarFeedPersonal,
     viewCalendarSchool, viewCalendarPersonal, viewCalendarSpaceBooking, fields
) VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?,?, ?, ?, ?, ?,
 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
  ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

// Prepare the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind values to the statement
$stmt->bind_param("ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
    $title, $preferredName, $officialName, $gender, $username, $status, $canLogin, $gibbonRoleIDPrimary, $dob, $email, $emailAlternate,
    $address1, $address1District, $address1Country, $phone1, $countryOfBirth, $emergency1Name, $emergency1Number1, $emergency1Number2,
    $emergency1Relationship, $emergency2Name, $emergency2Number1, $emergency2Number2, $emergency2Relationship, $studentID, $dateStart,
    $lastSchool, $privacy, $studentAgreements, $dayType, $nameInCharacters, $passwordStrong, $passwordStrongSalt, $gibbonRoleIDAll, $address2, $address2District, $address2Country, $phone2
    , $phone1CountryCode, $phone2CountryCode, $phone3CountryCode, $phone3, $phone4CountryCode, $phone4, $website, $languageFirst, $languageSecond, $languageThird
    , $gibbonApplicationFormID, $lockerNumber, $vehicleRegistration, $personalBackground, $messengerLastRead, $gibbonThemeIDPersonal, $gibboni18nIDPersonal
    , $googleAPIRefreshToken, $microsoftAPIRefreshToken, $genericAPIRefreshToken, $receiveNotificationEmails, $mfaSecret, $mfaToken, $cookieConsent
    , $image_240, $lastIPAddress, $lastTimestamp, $lastFailIPAddress, $gibbonSchoolYearIDClassOf,$birthCertificateScan, $ethnicity, $religion
    , $profession, $employer, $jobTitle, $nextSchool, $departureReason, $transport, $transportNotes, $calendarFeedPersonal
    , $viewCalendarSchool, $viewCalendarPersonal, $viewCalendarSpaceBooking, $fields
);

// Execute the statement
if ($stmt->execute()) {
    echo "New student record created successfully!";
} else {
    $error = $stmt->error;
    if (empty($error)) {
        echo "Error creating record (no error message available)";
    } else {
        echo "Error creating record: " . $error;
}
}
// Close statement and connection
$stmt->close();
$conn->close();
?>
