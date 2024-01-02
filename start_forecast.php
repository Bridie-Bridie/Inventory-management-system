<?php
include_once("includes/load.php");
include_once("layouts/newheader.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>CSV Upload</title>
</head>
<body>
<div class="prdctdiv">

    <h2>Upload CSV File</h2>
    <form method="post" enctype="multipart/form-data" action="forecast.php">
        <input type="file" name="csv_file" accept=".csv">
        <input type="submit" value="Upload">
    </form>
</div>
</body>
</html>
