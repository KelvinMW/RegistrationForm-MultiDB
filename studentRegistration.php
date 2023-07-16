<?php
//include config
include ("config.php");
// Retrieve form data
$surname = $_POST['thirdName'];
$firstName = $_POST['firstName'];
$preferredName = $_POST['firstName'].' '.$_POST['secondName'];
$officialName =$_POST['firstName'].' '.$_POST['secondName'].' '.$_POST['thirdName'];
// Retrieve other form fields

// Get selected database
$selectedDatabase = $_POST['database'];


// Choose the appropriate database based on the selection
switch ($selectedDatabase) {
    case "database1":
        $database = $database1;
        break;
    case "database2":
        $database = $database2;
        break;
    case "database3":
        $database = "database3";
        break;
    case "database4":
        $database = "database4";
        break;
    case "database5":
        $database = "database5";
        break;
    default:
        // Handle invalid selection or fallback to a default database
        $database = "database1";
}
//set default values on mandatory fields to default best values

// Create a new PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name) VALUES (?)");

    // Bind parameters and execute the statement
    $stmt->bindParam(1, $firstName, $officialName, $preferredName, $surname);
    // Bind other form fields

    $stmt->execute();

    echo "Registration successful!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>