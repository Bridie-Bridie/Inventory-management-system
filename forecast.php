 <?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("includes/load.php");
include_once("layouts/newheader.php");


require 'includes/Matrix.php';
use Cozy\ValueObjects\Matrix;

// Check if the form was submitted
if(isset($_POST['submit'])) {
    // Check if a file was uploaded successfully
    if(isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $uploadedFilePath = $_FILES['csvFile']['tmp_name'];

        // EXTRACT PHASE

        $input_data = [];

        if (($handle = fopen($uploadedFilePath, 'rb')) !== false) {
            $i = -1;

            while (($row = fgetcsv($handle, 100, ',')) !== false) {
                $i++;

                // Ignoring the headers
                if ($i === 0) {
                    continue;
                }

                $input_data[$i] = [
                    'period' => $i,
                    'date' => $row[0],
                    'sales' => $row[1],
                    'mbudget' => $row[2],
                    'frelease' => $row[3],
                    'discount' => $row[4],
                ];
            }

            fclose($handle);
        }

        // TRANSFORM PHASE

        $dependent_var = [];
        $independent_vars = [];
        $future_independent_vars = [];
        $result = [];

        foreach ($input_data as $datum) {
            $dt = new DateTimeImmutable($datum['date']);

            $vars = [
                1, // β₀
                $datum['period'],
                (float)$datum['mbudget'],
                (float)$datum['frelease'],
                (float)$datum['discount'],
            ];

            if ($dt->format('m') === '01') {
                $vars[] = 1;
            } else {
                $vars[] = 0;
            }

            if ($dt->format('m') === '02') {
                 $vars[] = 1;
            } else {
                 $vars[] = 0;
            }

            if ($dt->format('m') === '03') {
                 $vars[] = 1;
            } else {
                 $vars[] = 0;
            }

            if ($dt->format('m') === '04') {
                  $vars[] = 1;
            } else {
                  $vars[] = 0;
            }

            if ($dt->format('m') === '05') {
                  $vars[] = 1;
            } else {
                 $vars[] = 0;
            }

            if ($dt->format('m') === '06') {
                   $vars[] = 1;
            } else {
                   $vars[] = 0;
            }

            if ($dt->format('m') === '07') {
                  $vars[] = 1;
            } else {
                   $vars[] = 0;
            }

            if ($dt->format('m') === '08') {
                 $vars[] = 1;
            } else {
                   $vars[] = 0;
            }

            if ($dt->format('m') === '09') {
                 $vars[] = 1;
            } else {
                 $vars[] = 0;
            }

             if ($dt->format('m') === '10') {
                 $vars[] = 1;
            } else {
                 $vars[] = 0;
            }

            if ($dt->format('m') === '11') {
                 $vars[] = 1;
            } else {
                 $vars[] = 0;
            } 

            if ($datum['sales']) {
                $dependent_var[] = [(float)$datum['sales']];
                $independent_vars[] = $vars;
            }

            $result[$datum['period']] = [
                'date' => $datum['date'],
                'month' => $dt->format('M Y'),
                'sales' => $datum['sales'] ? (float)$datum['sales'] : null,
                'forecast' => null,
                'error_rate' => null,
                'independent_vars' => $vars,
            ];
        }

        // SUPERVISED TRAINING PHASE

        $X = new Matrix($independent_vars);
        $y = new Matrix($dependent_var);

        $B = $X
            ->transpose()
            ->multiply($X)
            ->inverse()
            ->multiply($X->transpose()->multiply($y));

        $coefficients = $B->getColumnValues(1);

        // PREDICTION PHASE

        $error_rates = [];

        foreach ($result as $period => $data) {
            $forecast = 0;
            foreach ($coefficients as $index => $coefficient) {
                $forecast += round($coefficient * $data['independent_vars'][$index], 3);
            }

            $result[$period]['forecast'] = $forecast;

            if ($data['sales']) {
                $error_rate = round(abs($data['sales'] - $forecast) / $data['sales'], 3);
                $error_rates[] = $result[$period]['error_rate'] = $error_rate;
            }
        }

        $average_error_rate = round(array_sum($error_rates) / count($error_rates) * 100, 1);

        // LOAD PHASE
   $fp = fopen('result.csv', 'wb');

foreach ($result as $data) {
    $row = [
        $data['date'],
        $data['month'],
        $data['sales'],
        $data['forecast'],
        $data['error_rate'],
    ];

    fputcsv($fp, $row);
}

fclose($fp);

// Output the average error rate
echo "\nAverage Error Rate: {$average_error_rate}%\n";
echo "CSV file processed and results saved to 'result.csv'";
}
}
require "upload2.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload and Process CSV File</title>
</head>
<body>
    <div class="prdctdiv">
    <h1>Upload and Process CSV File</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="csvFile">Select a CSV file:</label>
        <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
        <button type="submit" name="submit">Process CSV</button>
    </form>
    </div>
</body>
</html>
