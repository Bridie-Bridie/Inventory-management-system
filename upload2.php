
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div class="prdctdiv">

<?php

// MySQL database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chart";

// CSV file path
$csvFile = "result.csv";

// Create a new MySQLi object
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Empty the table
$tableName = "linechar";
$sqlEmptyTable = "TRUNCATE TABLE $tableName";
if ($conn->query($sqlEmptyTable) === TRUE) {
    echo "Table emptied successfully.<br>";
} else {  echo "Error emptying table: " . $conn->error; }

// Open the CSV file
$file = fopen($csvFile, 'r');

// Read the header row and do nothing (to skip the unwanted column)
$header = fgetcsv($file);

// Prepare the INSERT statement
$stmt = $conn->prepare("INSERT INTO linechar (period, sales, forecast, error) VALUES (?, ?, ?, ?)");

// Bind variables to the prepared statement
$stmt->bind_param("ssss", $period, $sales, $forecast, $error);

// Initialize a variable to count the number of rows inserted
$rowsInserted = 0;

// Read and insert data from the CSV file
while (($data = fgetcsv($file)) !== false) {
    // Check if the data array contains the required keys (3 columns excluding the unwanted one)
    if (count($data) >= 3) {
        // Assign values to variables, skipping the second column
        $period = $data[0];     // Assuming the period column is in the first position (index 0)
        $sales = $data[2];      // Assuming the sales column is in the third position (index 2)
        $forecast = $data[3];   // Assuming the forecast column is in the fourth position (index 3)
        $error = $data[4];      // Assuming the error column is in the fifth position (index 4)
        
        // Execute the prepared statement
        if ($stmt->execute()) {
            $rowsInserted++;
        } else {
            echo "Error inserting row: " . $conn->error;
        }
    } else {
        // Handle missing or incorrect data here, e.g., logging the issue or skipping the row
        echo "Skipping row due to missing or incorrect data.<br>";
    }
}

// Close the prepared statement
$stmt->close();

// Close the file
fclose($file);

// Close the database connection
$conn->close();

// Display a message indicating the number of rows inserted
if ($rowsInserted > 0) {
    echo "$rowsInserted row(s) inserted successfully.";
} else {
    echo "No rows inserted.";
}
include "line.php";
?>

</div>
</body>
</html>
