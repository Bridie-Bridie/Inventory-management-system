<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");
// Check if the form is submitted and process the data
if (isset($_POST['selected_month'])) {
    // Replace "your_host", "your_username", "your_password", and "your_database" with your actual database credentials.
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "newproject";

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $selectedMonth = $_POST['selected_month'];

    // Get the selected year and month from the user input
    list($selectedYear, $selectedMonth) = explode('-', $selectedMonth);

    // Assuming you have tables named "sales" and "products" with appropriate columns
    $sql = "SELECT p.name, s.qty, s.price, s.date 
            FROM sales s
            LEFT JOIN products p ON s.product_id = p.id 
            WHERE MONTH(s.date) = '$selectedMonth' AND YEAR(s.date) = '$selectedYear'";
    
    $result = $connection->query($sql);

    // Create an array to store the monthly report data
    $monthlyReport = array();

    // Loop through the query result and populate the monthly report array
    while ($row = $result->fetch_assoc()) {
        // Process and store data as needed for the report
        $monthlyReport[] = array(
            'product_name' => $row['name'],
            'quantity' => $row['qty'],
            'price' => $row['price'],
            'date' => $row['date'],
        );
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monthly Report</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Generate Monthly Report</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="selected_month">Select Month:</label>
        <input type="month" id="selected_month" name="selected_month">
        <button type="submit">Generate Report</button>
    </form>

    <?php
    // Display the monthly report if it is generated
    if (isset($monthlyReport)) {
        $selectedDate = $selectedYear . '-' . $selectedMonth . '-01'; // Append day to the date
        echo "<h2>Monthly Report for " . date('F Y', strtotime($selectedDate)) . "</h2>";
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Date</th></tr>";

        foreach ($monthlyReport as $row) {
            echo "<tr>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    ?>
    <form action="generate_pdf2.php" method="post">
        <label for="selected_month">Select Month:</label>
        <input type="month" id="selected_month" name="selected_month">
        <button type="submit" name="generate_monthly_pdf">Generate Monthly PDF</button>
    </form>

</div>
</body>
</html>
