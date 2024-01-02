 <?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT period, sales, forecast, error FROM linechar";
$result = $conn->query($sql);

// Create an array to hold the data
$data = array();
$data[] = ['Period', 'Sales', 'Forecast', 'Error'];

if ($result->num_rows > 0) {
    // Loop through each row of data
    while ($row = $result->fetch_assoc()) {
        $period = $row['period'];
        $sales = (float) $row['sales'];
        $forecast = (float) $row['forecast'];
        $error = (float) $row['error'];

        // Add the data to the array
        $data[] = [$period, $sales, $forecast, $error];
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
 <head> 
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

            var options = {
                chart: {
                    title: 'Sales and Forecast',
                    subtitle: 'Period, Sales, Forecast, and Error',
                },
                width: 900,
                height: 500,
                
            };

            var chart = new google.charts.Line(document.getElementById('chart_div'));

            chart.draw(data, google.charts.Line.convertOptions(options));
        }
    </script>
 </head>
    <body>
        <div class="prdctdiv"> 
        <div id="chart_div"> </div>
        
    </body>
</html>