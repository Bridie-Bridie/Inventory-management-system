
<?php    

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'newproject';

// Connect to the database
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total sales for a specific year and month
function getMonthlySales($conn, $year, $month) {
    $query = "SELECT SUM(price) AS total_sales FROM sales WHERE YEAR(date) = $year AND MONTH(date) = $month";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_sales'];
    } else {
        return 0;
    }
}

// Function to get marketing budget and discount offer for a specific year and month
function getMonthlyMarketingData($conn, $year, $month) {
    $query = "SELECT marketing_budget, discount_offer FROM monthly_settings WHERE YEAR(month) = $year AND MONTH(month) = $month";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return array('marketing_budget' => 0, 'discount_offer' => 0);
    }
}

// Function to generate the year report data in CSV format
function generateYearReportCSV($conn, $year) {
    $csvData = "Month,Total Sales,Marketing Budget,New Releases,Discount Offer\n";

    for ($month = 1; $month <= 12; $month++) {
        $monthlySales = getMonthlySales($conn, $year, $month);
        $marketingData = getMonthlyMarketingData($conn, $year, $month);

        $monthName = date("m/d/y", mktime(0, 0, 0, $month, 1, $year));
        $marketingBudget = $marketingData['marketing_budget'];
        $discountOffer = $marketingData['discount_offer'];

        // Query to count new releases in the products table for the specific month
        $newReleasesQuery = "SELECT COUNT(*) AS new_releases FROM products WHERE YEAR(date) = $year AND MONTH(date) = $month";
        $newReleasesResult = $conn->query($newReleasesQuery);
        $newReleases = 0;
        if ($newReleasesResult && $newReleasesResult->num_rows > 0) {
            $newReleasesRow = $newReleasesResult->fetch_assoc();
            $newReleases = $newReleasesRow['new_releases'];
        }

        $csvData .= "$monthName,$monthlySales,$marketingBudget,$newReleases,$discountOffer\n";
    }

    return $csvData;
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the user input for the year
    $yearToRetrieve = $_POST['year'];

    // Generate the year report for the chosen year
    $csvFileName = "year_report_" . $yearToRetrieve . ".csv";
    $csvData = generateYearReportCSV($conn, $yearToRetrieve);

    // Create the CSV file and trigger download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '";');
    echo $csvData;
    exit(); // Stop further execution of the script after download
}

$conn->close();

include_once("includes/load.php");
include_once("layouts/newheader.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Year Report</title>
</head>
<body>
    <div class="prdctdiv">
        <h1>Select a Year</h1>
        <form method="post">
            <label for="year">Choose a year:</label>
            <input type="number" id="year" name="year" min="2000" max="2099" required>
            <input type="submit" name="submit" value="Generate Report and Download CSV">
        </form>

        <?php if (isset($_POST['submit'])) : ?>
            <?php
            // Get the user input for the year
            $yearToRetrieve = $_POST['year'];
            ?>
            <h2>Year Report for <?php echo $yearToRetrieve; ?></h2>
            <table border='1'>
                <tr>
                    <th>Month</th>
                    <th>Total Sales</th>
                    <th>Marketing Budget</th>
                    <th>Discount Offer</th>
                    <th>New Releases</th>
                </tr>
                <?php
                for ($month = 1; $month <= 12; $month++) {
                    $monthlySales = getMonthlySales($conn, $yearToRetrieve, $month);
                    $marketingData = getMonthlyMarketingData($conn, $yearToRetrieve, $month);

                    $monthName = date("m/d/y", mktime(0, 0, 0, $month, 1, $yearToRetrieve));
                    $marketingBudget = $marketingData['marketing_budget'];
                    $discountOffer = $marketingData['discount_offer'];
                    $newReleases = $marketingData['new_releases'];

                    echo "<tr>";
                    echo "<td>$monthName</td>";
                    echo "<td>$monthlySales</td>";
                    echo "<td>$marketingBudget</td>";
                    echo "<td>$newReleases</td>";
                    echo "<td>$discountOffer</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

</div>
</body>
</html>