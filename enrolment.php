<?php
include 'config.php';
include 'registration.php';
function enrollStudent($conn, $gibbonPersonID, $gibbonFormGroupID, $gibbonYearGroupID)
{
    $sql = "INSERT INTO gibbonStudentEnrolment (gibbonPersonID, gibbonFormGroupID, gibbonYearGroupID) VALUES ($gibbonPersonID, $gibbonFormGroupID, $gibbonYearGroupID)";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
