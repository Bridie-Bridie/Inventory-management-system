<?php


while (ob_get_level())
    ob_end_clean();
header("Content-Encoding: None", true);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf/fpdf.php'); // Adjust the path to the FPDF library file

$host = "localhost";
$username = "root";
$password = "";
$database = "newproject";

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the form is submitted and process the data
if (isset($_POST['generate_monthly_pdf'])) {
    
    $selectedMonth = $_POST['selected_month'];
    list($selectedYear, $selectedMonth) = explode('-', $selectedMonth);

    $startDate = $selectedYear . '-' . $selectedMonth . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));

    $sql = "SELECT p.name, s.qty, s.price, s.date 
            FROM sales s
            LEFT JOIN products p ON s.product_id = p.id 
            WHERE DATE(s.date) BETWEEN '$startDate' AND '$endDate'";

    $result = $connection->query($sql);

    $monthlySalesReport = array();

    while ($row = $result->fetch_assoc()) {
        $monthlySalesReport[] = array(
            'product_name' => $row['name'],
            'quantity' => $row['qty'],
            'price' => $row['price'],
            'date' => $row['date'],
        );
    }

    // Generate PDF report
    class PDF extends FPDF {
        private $selectedMonth;
        private $monthlySalesReport;

        function __construct($selectedMonth, $monthlySalesReport) {
            parent::__construct();
            $this->selectedMonth = $selectedMonth;
            $this->monthlySalesReport = $monthlySalesReport;
        }

        function Header() {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(20, 10, 'Cupcake bakery ' ,  1, 1, 'C' );
            $this->Cell(0, 10, 'Monthly Sales Report for ' . date('F Y', strtotime($this->selectedMonth)), 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            // Add footer content if needed
        }
    }

    $pdf = new PDF($startDate, $monthlySalesReport);
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 12);

    if (count($monthlySalesReport) > 0) {
        $header = array('Product Name', 'Quantity', 'Price', 'Date');
        $data = array();

        foreach ($monthlySalesReport as $row) {
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

        $pdf->SetFont('Arial', '', 12);

        foreach ($data as $row) {
            foreach ($row as $col) {
                $pdf->Cell(47.5, 10, $col, 1);
            }
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No sales made for this month.', 0, 1, 'C');
    }

    // Output PDF to browser
    $pdf->Output('monthly_sales_report.pdf', 'D'); // 'D' to force download

    ob_end_flush(); // Discard the output buffer content
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Monthly Sales Report</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Generate Monthly Sales Report</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="selected_month">Select Month:</label>
        <input type="month" id="selected_month" name="selected_month">
        <button type="submit" name="generate_monthly_pdf">Generate Monthly PDF</button>
    </form>
</div>S
</body>
</html>
