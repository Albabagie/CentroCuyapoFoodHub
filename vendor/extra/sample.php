<?php


require_once 'connection.php';

$sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amt) as total_sales
        FROM orders o 
        LEFT JOIN order_total ot ON o.order_id = ot.order_id
        GROUP BY month
        ORDER BY month";

$result = $conn->query($sql);

$dates = [];
$sales = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['month'];
    $sales[] = $row['total_sales'];
}

$monthNames = [];
foreach ($dates as $date) {
    $dateTime = new DateTime($date . '-01');
    $monthNames[] = $dateTime->format('F');
}

function calculateForecast($sales)
{
    $forecast = [];
    for ($i = 2; $i < count($sales); $i++) {
        $forecast[] = round(($sales[$i - 2] + $sales[$i - 1] + $sales[$i]) / 3);
    }
    return $forecast;
}

function calculateNextMonthForecast($sales)
{
    $forecast = calculateForecast($sales);
    $lastThreeSales = array_slice($sales, -3);
    $nextMonthForecast = round(array_sum($lastThreeSales) / 3);
    $forecast[] = $nextMonthForecast;
    return $forecast;
}

$forecast = calculateNextMonthForecast($sales);

$percentageIncreases = [];
for ($i = 1; $i < count($sales); $i++) {
    $percentageIncreases[] = round((($sales[$i] - $sales[$i - 1]) / $sales[$i - 1]) * 100, 2);
}




?>

<!DOCTYPE html>
<html>

<head>
    <title>Sales Forecasting</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<style>
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        width: 200px;
        float: left;
    }
</style>

<body>

    <div id="chart"></div>

    <?php for ($i = 4; $i < count($sales); $i++) { ?>
        <div class="card">
            <h3>Sales Increase</h3>
            <p>From <?php echo $dates[$i - 1]; ?> to <?php echo $dates[$i]; ?></p>
            <p><?php echo $percentageIncreases[$i - 1]; ?>% increase</p>
        </div>
    <?php } ?>
    <script>
        var monthNames = <?php echo json_encode($monthNames); ?>;
        var sales = <?php echo json_encode($sales); ?>;
        var forecast = <?php echo json_encode($forecast); ?>;

        var options = {
            chart: {
                type: 'line',
                height: 350,
                width: 800
            },
            series: [{
                name: 'Sales',
                data: sales
            }, {
                name: 'Forecast',
                data: [null, null].concat(forecast)
            }],
            xaxis: {
                categories: monthNames.concat('<?php echo date("F", strtotime(end($monthNames) . "+1 month")); ?>')
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

</body>

</html>