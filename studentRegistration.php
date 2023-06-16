<?php
//include config
include ("config.php");
// Retrieve form data
$name = $_POST['name'];
// Retrieve other form fields

// Get selected database
$selectedDatabase = $_POST['database'];

// Establish database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";

// Choose the appropriate database based on the selection
switch ($selectedDatabase) {
    case "database1":
        $database = "database1";
        break;
    case "database2":
        $database = "database2";
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

// Create a new PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name) VALUES (?)");

    // Bind parameters and execute the statement
    $stmt->bindParam(1, $name);
    // Bind other form fields

    $stmt->execute();

    echo "Registration successful!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>