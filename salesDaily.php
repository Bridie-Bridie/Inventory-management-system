<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");

$host = "localhost";
$username = "root";
$password = "";
$database = "newproject";

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$currentDate = date('Y-m-d'); // Get the current date in "YYYY-MM-DD" format

$sql = "SELECT p.name, s.qty, s.price, s.date 
        FROM sales s
        LEFT JOIN products p ON s.product_id = p.id 
        WHERE DATE(s.date) = '$currentDate'";

$result = $connection->query($sql);

// Create an array to store the daily sales data
$dailySalesReport = array();

// Loop through the query result and populate the daily sales array
while ($row = $result->fetch_assoc()) {
    // Process and store data as needed for the report
    $dailySalesReport[] = array(
        'product_name' => $row['name'],
        'quantity' => $row['qty'],
        'price' => $row['price'],
        'date' => $row['date'],
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Daily Sales Report for <?php echo date('F j, Y', strtotime($currentDate)); ?></h1>
    <?php if (count($dailySalesReport) > 0) : ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Date</th>
            </tr>
            <?php foreach ($dailySalesReport as $row) : ?>
                <tr>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No sales made for today.</p>
    <?php endif; ?>
    <form action="generate_pdf.php" method="post">
            <button type="submit" name="generate_pdf">Generate PDF</button>
        </form>
</div>
</body>
</html>
