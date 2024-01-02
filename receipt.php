<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newproject";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['sale_id'])) {
    $sale_id = (int)$_GET['sale_id'];
    
    // Retrieve sale details from the database using the $conn object
    $query = "SELECT s.product_id, p.name AS product_name, s.qty, s.price AS discounted_price, s.date
              FROM sales s
              LEFT JOIN products p ON s.product_id = p.id
              WHERE s.id = '{$sale_id}'";
    $result = $conn->query($query); // Use $conn here
    if ($result->num_rows > 0) {
        $sale = $result->fetch_assoc(); // Use fetch_assoc() here
    } else {
        // Sale not found
        $session->msg('d', 'Sale not found.');
        redirect('newsale.php', false);
    }
} else {
    // Sale ID not provided
    $session->msg('d', 'Invalid sale ID.');
    redirect('newsale.php', false);
}
?>

<div class="receipt">
    <h2>Sale Receipt</h2>
    <p><strong>Date:</strong> <?php echo $sale['date']; ?></p>
    <p><strong>Product:</strong> <?php echo $sale['product_name']; ?></p>
    <p><strong>Quantity:</strong> <?php echo $sale['qty']; ?></p>
    <p><strong>Discounted Price:</strong> <?php echo $sale['discounted_price']; ?></p>
    <!-- You can include more details such as customer name, payment method, etc. -->
    <p><a href="newsale.php">Back to Sales</a></p>
</div>

<?php include_once('layouts/newfooter.php'); ?>
