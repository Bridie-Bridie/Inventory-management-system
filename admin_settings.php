<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");
include "view_settings.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newproject";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update or insert the settings in the database
function updateSettings($conn, $selectedDate, $discount_offer, $marketing_budget) {
    $sql = "INSERT INTO monthly_settings (month, discount_offer, marketing_budget) VALUES ('$selectedDate', '$discount_offer', '$marketing_budget') ON DUPLICATE KEY UPDATE discount_offer = VALUES(discount_offer), marketing_budget = VALUES(marketing_budget)";
    $conn->query($sql);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST["selected_date"];
    $discount_offer = floatval($_POST["discount_offer"]);
    $marketing_budget = floatval($_POST["marketing_budget"]);

    // Call the function to update or insert the settings
    updateSettings($conn, $selectedDate, $discount_offer, $marketing_budget);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Settings</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Admin Settings</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="selected_date">Select Date:</label>
        <input type="date" name="selected_date"><br> <!-- Use date input type to select the date -->

        <label for="discount_offer">Discount Offer:</label>
        <input type="number" step="0.01" name="discount_offer"><br>

        <label for="marketing_budget">Marketing Budget:</label>
        <input type="number" step="0.01" name="marketing_budget"><br>

        <input type="submit" value="Set for Selected Date">
    </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
