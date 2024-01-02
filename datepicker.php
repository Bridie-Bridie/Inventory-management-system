<?php 
 include_once("includes/load.php");
 include_once("layouts/newheader.php");
 ?>
<div class="prdctdiv">
    <h3>Report Generation</h3>
    <div class="eduser">
        <form method="post" action="sales_report_processing.php">
            From <input type="date" placeholder="From" name="strtdate">
            To <input type="date" placeholder="To" name="endate"><br><br>
            <button type="submit" name="submit" class="btn btn-outline-success">Submit</button>
        </form>
    </div>
</div>