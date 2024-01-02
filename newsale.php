<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");

// Function to get the discount for the current month from the database
function get_discount_for_current_month($db) {
    $current_month = date('m');
    $query = "SELECT discount_offer FROM monthly_settings WHERE MONTH(month) = '{$current_month}' ORDER BY created_at DESC LIMIT 1";
    $result = $db->query($query);
    if ($db->num_rows($result) > 0) {
        $row = $db->fetch_assoc($result);
        return (float)$row['discount_offer'];
    }
    return 0.0; // Return 0 discount if no entry found for the current month
}

// Function to check if the product quantity is zero or not
function is_product_available($db, $product_id, $quantity) {
    $query = "SELECT quantity FROM products WHERE id = '{$product_id}'";
    $result = $db->query($query);
    if ($db->num_rows($result) > 0) {
        $row = $db->fetch_assoc($result);
        $available_quantity = (int)$row['quantity'];
        return $available_quantity >= $quantity;
    }
    return false; // Return false if the product is not found in the database
}

if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id','quantity','total', 'date' );
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id      = $db->escape((int)$_POST['s_id']);
        $s_qty     = $db->escape((int)$_POST['quantity']);
        $s_total   = $db->escape($_POST['total']);
        $date      = $db->escape($_POST['date']);
        $s_date    = make_date();

        // Check if the product is available before proceeding with the sale
        if (!is_product_available($db, $p_id, $s_qty)) {
            $session->msg('d', 'Sale restricted. Product is out of stock.');
            redirect('prdct.php', false);
        }

        // Calculate the discount for the current month
        $discount = get_discount_for_current_month($db);
        $discounted_total = $s_total - ($s_total * $discount / 100);

        $sql  = "INSERT INTO sales (";
        $sql .= " product_id,qty,price,date";
        $sql .= ") VALUES (";
        $sql .= "'{$p_id}','{$s_qty}','{$discounted_total}','{$s_date}'";
        $sql .= ") ";

        if ($db->query($sql)) {
            update_product_qty($s_qty, $p_id);

            // Set the session message as an array with the message type and text
            $_SESSION['msg'] = array('s', "Sale added. Total Discounted Price: {$discounted_total}");
            redirect('newsale.php', false);
        } else {
            $session->msg('d', 'Sorry failed to add!');
            redirect('prdct.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('prdct.php', false);
    }
}
// Fetch sales data from the database
$sales_query = "SELECT * FROM sales ORDER BY date DESC";
$sales_result = $db->query($sales_query);
$sales_data = [];
while ($row = $db->fetch_assoc($sales_result)) {
    $sales_data[] = $row;
}
?>



<div class="prdctdiv">
    <?php echo display_msg($msg); ?>

    <!-- Display the discount for the current month -->
    <div class="discount-div">
        <?php
            $discount = get_discount_for_current_month($db);
            if ($discount > 0) {
                echo "Discount for this month: {$discount}%";
            } else {
                echo "No discount available for this month.";
            }
        ?>
    </div>

    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <button type="submit"> Find It</button>
        <input type="text" id="sug_input" name="title" class="form-control" placeholder="Search for product name">
        <div id="result" class="list-group"></div>
    </form>
    <form method="post" action="newsale.php">
        <table>
            <thead>
                <th> Item </th>
                <th> Price </th>
                <th> Qty </th>
                <th> Total </th>
                <th> Date</th>
                <th> Action</th>
            </thead>
            <tbody  id="product_info"> </tbody>
        </table>
    </form>

</div>

<?php include_once('layouts/newfooter.php'); ?>

<script>
    // Check if there is a session message for successful sale addition
    var msg = '<?php echo isset($_SESSION['msg']) ? json_encode($_SESSION['msg']) : ""; ?>';
    if (msg) {
        var message = JSON.parse(msg);
        alert(message[1]); // Display the notification with the total discounted price
        <?php unset($_SESSION['msg']); ?> // Clear the session message after displaying the notification
    }
</script>
