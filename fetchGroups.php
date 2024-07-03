<?php
include 'config.php';

// Check if the request is made via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['database'])) {
    $databases = [
        "kindergarten_database" => "elm_kg",
        "lower_primary_database" => "elm_lower-p",
        "upper_primary_database" => "elm_upper-p",
        "secondary_database" => "elm_secondary",
        "science_secondary_database" => "elm_science-secondary"
    ];

    $dbname = $databases[$_POST['database']] ?? 'elm_secondary';

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }
    $gibbonSchoolYearID = 1;
    $currentSchoolYearQuery = "SELECT gibbonSchoolYearID FROM gibbonSchoolYear WHERE status = 'Current'";
    $currentSchoolYearResult = $conn->query($currentSchoolYearQuery);
    if ($currentSchoolYearResult->num_rows > 0) {
        $currentSchoolYearID = $currentSchoolYearResult->fetch_assoc()['gibbonSchoolYearID'];
    } else {
        echo json_encode(['error' => 'No current school year found.']);
        exit();
    }

    $yearGroupQuery = "SELECT gibbonYearGroupID, name FROM gibbonYearGroup";
    $yearGroupResult = $conn->query($yearGroupQuery);
    $yearGroups = [];
    while ($row = $yearGroupResult->fetch_assoc()) {
        $yearGroups[] = $row;
    }

    $formGroupQuery = "SELECT gibbonFormGroupID, name FROM gibbonFormGroup WHERE gibbonSchoolYearID = $currentSchoolYearID";
    $formGroupResult = $conn->query($formGroupQuery);
    $formGroups = [];
    while ($row = $formGroupResult->fetch_assoc()) {
        $formGroups[] = $row;
    }

    $conn->close();

    echo json_encode(['yearGroups' => $yearGroups, 'formGroups' => $formGroups]);
}

?>
