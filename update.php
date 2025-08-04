<?php


require_once 'connection.php';
$sql1 ="SELECT 
            DATE_FORMAT(CURRENT_DATE(), '%M') AS current_month,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE())
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE())
                   ), 0) AS current_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH, '%M') AS previous_month,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                   ), 0) AS previous_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 2 MONTH, '%M') AS two_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 2 MONTH)
                   ), 0) AS two_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH, '%M') AS three_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 3 MONTH)
                   ), 0) AS three_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 4 MONTH, '%M') AS four_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 4 MONTH)
                   ), 0) AS four_months_ago_sales";

$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
    $no_data = 0;
    while ($rows = $result1->fetch_assoc()) {
        //month
        $twoMonths1 = $rows['two_months_ago'];
        $threeMonths1 = $rows['three_months_ago'];
        $fourMonths1 = $rows['four_months_ago'];
        $previous_month1 = $rows['previous_month'];
        $current_month1 = $rows['current_month'];

        $last_two_months_sales1 = $rows['two_months_ago_sales'];
        $last_three_months_sales1 = $rows['three_months_ago_sales'];
        $last_four_months_sales1 = $rows['four_months_ago_sales'];
        $previous_sales1 = $rows['previous_month_sales'];
        $current_sales1 = $rows['current_month_sales'];
    }

}

$sql = "SELECT 
            DATE_FORMAT(CURRENT_DATE(), '%M') AS current_month,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE())
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE())
                   ), 0) AS current_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH, '%M') AS previous_month,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                   ), 0) AS previous_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 2 MONTH, '%M') AS two_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 2 MONTH)
                   ), 0) AS two_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH, '%M') AS three_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 3 MONTH)
                   ), 0) AS three_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 4 MONTH, '%M') AS four_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 4 MONTH)
                   ), 0) AS four_months_ago_sales";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $no_data = 0;
    while ($row = $result->fetch_assoc()) {
        //month
        $twoMonths = $row['two_months_ago'];
        $threeMonths = $row['three_months_ago'];
        $fourMonths = $row['four_months_ago'];
        $previous_month = $row['previous_month'];
        $current_month = $row['current_month'];

        $last_two_months_sales = $row['two_months_ago_sales'];
        $last_three_months_sales = $row['three_months_ago_sales'];
        $last_four_months_sales = $row['four_months_ago_sales'];
        $previous_sales = $row['previous_month_sales'];
        $current_sales = $row['current_month_sales'];
    }

    if ($previous_sales == 0 || $previous_sales === NULL) {
        $growth_rate = 0; 
    } else {
        $growth_rate = (($current_sales - $previous_sales)) / $previous_sales * 100;
    }

    if ($growth_rate == 0 || $current_sales === NULL) {
        $forecast_move = 0; 
    } else {
        $forecast_move = $current_sales * ($growth_rate / 100) ;
    }

    if ($forecast_move == 0 || $current_sales === NULL) {
        $forecast_sales = 0; 
    } else {
         $forecast_sales = $current_sales + $forecast_move;
    }
  
    
    $data = [
        'four_months' => [
            'month' => $fourMonths,
            'sales' => $last_two_months_sales + $last_two_months_sales1
        ],
        'three_months' => [
            'month' => $threeMonths,
            'sales' => $last_three_months_sales + $last_three_months_sales1
        ],
        'two_months' => [
            'month' => $twoMonths,
            'sales' => $last_four_months_sales + $last_four_months_sales1
        ],
        'previous_month' => [
            'month' => $previous_month,
            'sales' => $previous_sales + $previous_sales1
        ],
        'current_month' => [
            'month' => $current_month,
            'sales' => $current_sales + $current_sales1
        ],
        'forecast_sales' =>$forecast_sales,
        'growth_rate' => $growth_rate,
        'forecast_move' => $forecast_move
    ];

    echo json_encode($data);

} else {
    echo json_encode(['error' => 'No data found']);
}

