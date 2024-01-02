<?php
while (ob_get_level())
ob_end_clean();
header("Content-Encoding: None", true);


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf/fpdf.php'); // Adjust the path to the FPDF library file
 // Include the daily sales data retrieval script
$host = "localhost";
$username = "root";
$password = "";
$database = "newproject";

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$currentDate = date('Y-m-d'); // Get the current date in "YYYY-MM-DD" format

// Assuming you have tables named "sales" and "products" with appropriate columns
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

class PDF extends FPDF {
    private $currentDate;
    private $dailySalesReport;

    function __construct($currentDate, $dailySalesReport) {
        parent::__construct();
        $this->currentDate = $currentDate;
        $this->dailySalesReport = $dailySalesReport;
    }

    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20, 10, 'Cupcake bakery ' ,  1, 1,  'C' );
        $this->Cell(0, 10, 'Daily Sales Report for ' . date('F j, Y', strtotime($this->currentDate)), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Add footer content if needed
    }
}


$pdf = new PDF($currentDate, $dailySalesReport);
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 12); // Set font for header

if (count($dailySalesReport) > 0) {
    // Create a table
    $header = array('Product Name', 'Quantity', 'Price', 'Date');
    $data = array();

    foreach ($dailySalesReport as $row) {
        $data[] = array($row['product_name'], $row['quantity'], $row['price'], $row['date']);
    }

    $pdf->SetFillColor(200, 220, 255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0);
    $pdf->SetLineWidth(0.3);

    foreach ($header as $col) {
        $pdf->Cell(47.5, 10, $col, 1, 0, 'C', 1);
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12); // Set font for table data

    foreach ($data as $row) {
        foreach ($row as $col) {
            $pdf->Cell(47.5, 10, $col, 1);
        }
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No sales made for today.', 0, 1, 'C');
}

// Output PDF to browser
$pdf->Output('daily_sales_report.pdf', 'D'); // 'D' to force download

ob_end_flush(); // Discard the output buffer content
?>
