<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';
require 'fetchData.php';

$data = [];
$startDate = '';
$endDate = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        foreach ($databases as $database) {
            $fetchedData = fetchData($startDate, $endDate, $database);
            foreach ($fetchedData as &$row) {
                $row['database'] = $database; // Add database information to each row
            }
            $data = array_merge($data, $fetchedData);
        }

        // Store dates in session for CSV export
        $_SESSION['start_date'] = $startDate;
        $_SESSION['end_date'] = $endDate;
    }

    // Handle CSV export
    if (isset($_POST['export_csv'])) {
        exportCSV($data, $_SESSION['start_date'], $_SESSION['end_date']);
    }
}

// Function to export data to CSV
function exportCSV($data, $startDate, $endDate) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="report_' . $startDate . '_to_' . $endDate . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Database', 'Official Name', 'Gender', 'Username', 'DOB', 'Email', 'Address', 'Phone', 'Country of Birth', 'Emergency Contact 1', 'Emergency Contact 1 Number', 'Emergency Contact 2', 'Emergency Contact 2 Number', 'Form Group', 'Year Group']);

    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports Page</title>
</head>
<body>
    <h2>Reports</h2>
    <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required><br>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required><br>
        <button type="submit">Fetch Data</button>
        <button type="submit" name="export_csv">Export to CSV</button>
        <button type="button" onclick="printTable()">Print</button>
    </form>

    <?php if (!empty($data)): ?>
        <h3>Data from <?php echo htmlspecialchars($startDate); ?> to <?php echo htmlspecialchars($endDate); ?></h3>

        <!-- Filters -->
        <div>
            <label for="databaseFilter">Database:</label>
            <select id="databaseFilter" onchange="filterTable()">
                <option value="">All</option>
                <?php foreach ($databases as $database): ?>
                    <option value="<?php echo htmlspecialchars($database); ?>"><?php echo htmlspecialchars($database); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="yearGroupFilter">Year Group:</label>
            <select id="yearGroupFilter" onchange="filterTable()">
                <option value="">All</option>
                <!-- Generate Year Group options dynamically based on data -->
                <?php
                $yearGroups = array_unique(array_column($data, 'YearGroup'));
                foreach ($yearGroups as $yearGroup): ?>
                    <option value="<?php echo htmlspecialchars($yearGroup); ?>"><?php echo htmlspecialchars($yearGroup); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="genderFilter">Gender:</label>
            <select id="genderFilter" onchange="filterTable()">
                <option value="">All</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <!-- Add more gender options if necessary -->
            </select>
        </div>

        <table id="reportTable" border="1">
            <thead>
                <tr>
                    <th>Database</th>
                    <th>Official Name</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Country of Birth</th>
                    <th>Emergency Contact 1</th>
                    <th>Emergency Contact 1 Number</th>
                    <th>Emergency Contact 2</th>
                    <th>Emergency Contact 2 Number</th>
                    <th>Form Group</th>
                    <th>Year Group</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['database']); ?></td>
                        <td><?php echo htmlspecialchars($row['officialName']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['dob']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['address1']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone1']); ?></td>
                        <td><?php echo htmlspecialchars($row['countryOfBirth']); ?></td>
                        <td><?php echo htmlspecialchars($row['emergency1Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['emergency1Number1']); ?></td>
                        <td><?php echo htmlspecialchars($row['emergency2Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['emergency2Number2']); ?></td>
                        <td><?php echo htmlspecialchars($row['FormGroup']); ?></td>
                        <td><?php echo htmlspecialchars($row['YearGroup']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <nav>
        <ul>
            <li><a href="landing.php">Home</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <a href="logout.php">Logout</a>

    <script>
        function filterTable() {
            var databaseFilter = document.getElementById("databaseFilter").value.toLowerCase();
            var yearGroupFilter = document.getElementById("yearGroupFilter").value.toLowerCase();
            var genderFilter = document.getElementById("genderFilter").value.toLowerCase();

            var table = document.getElementById("reportTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var database = cells[0].innerText.toLowerCase();
                var yearGroup = cells[13].innerText.toLowerCase();
                var gender = cells[2].innerText.toLowerCase();

                if ((databaseFilter === "" || database.includes(databaseFilter)) &&
                    (yearGroupFilter === "" || yearGroup.includes(yearGroupFilter)) &&
                    (genderFilter === "" || gender.includes(genderFilter))) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function printTable() {
            var divToPrint = document.getElementById("reportTable");
            var newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }
    </script>
</body>
</html>
