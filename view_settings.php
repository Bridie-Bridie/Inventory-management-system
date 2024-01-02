<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");
// Connect to the database (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newproject";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get all past months' settings from the database
function getPastMonthsSettings($conn) {
    $sql = "SELECT month, discount_offer, marketing_budget FROM monthly_settings ORDER BY id DESC";
    $result = $conn->query($sql);

    $pastMonthsSettings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pastMonthsSettings[] = $row;
        }
    }

    return $pastMonthsSettings;
}

// Call the function to get past months' settings
$pastMonthsSettings = getPastMonthsSettings($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Past Months' Settings</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Past Months' Settings</h1>
    <table>
        <tr>
            <th>Month</th>
            <th>Discount Offer</th>
            <th>Marketing Budget</th>
            
        </tr>
        <?php foreach ($pastMonthsSettings as $settings) { ?>
            <tr>
                <td><?php echo $settings['month']; ?></td>
                <td><?php echo $settings['discount_offer']; ?></td>
                <td><?php echo $settings['marketing_budget']; ?></td>
            
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
