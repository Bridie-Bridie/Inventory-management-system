<?php
    include_once("includes/load.php");
    include_once("layouts/newheader.php");
    include "year_report.php"
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Year Report</title>
</head>
<body>
	<div class="prdctdiv">
    <h1>Select a Year</h1>
    <form method="post" action="year_report.php">
        <label for="year">Choose a year:</label>
        <input type="number" id="year" name="year" min="2000" max="2099" required>
        <input type="submit" name="submit" value="Generate Report">
    </form>
</div>
</body>
</html>
