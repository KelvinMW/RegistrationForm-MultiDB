<?php
$databases = ['elm_secondary', 'elm_science-secondary'];
function fetchData($startDate, $endDate, $database) {
    require 'config.php';

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "
    SELECT 
        gp.officialName, gp.gender, gp.username, gp.dob, gp.email, gp.address1, gp.phone1, gp.countryOfBirth,
        gp.emergency1Name, gp.emergency1Number1, gp.emergency2Name, gp.emergency2Number2,
        gf.name as FormGroup, gy.name as YearGroup
    FROM gibbonPerson gp
    LEFT JOIN gibbonStudentEnrolment gse ON gp.gibbonPersonID = gse.gibbonPersonID
    LEFT JOIN gibbonFormGroup gf ON gse.gibbonFormGroupID = gf.gibbonFormGroupID
    LEFT JOIN gibbonYearGroup gy ON gse.gibbonYearGroupID = gy.gibbonYearGroupID
    WHERE gp.dateStart BETWEEN ? AND ?
";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $data;
}
?>
