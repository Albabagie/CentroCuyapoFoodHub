<?php
include("session.php");

// Query for OTC sales
$sql1 = "SELECT 
            DATE_FORMAT(CURRENT_DATE(), '%M') AS current_month,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE())
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE())
                    AND od.over_status = 3
                   ), 0) AS current_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH, '%M') AS previous_month,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND od.over_status = 3
                   ), 0) AS previous_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 2 MONTH, '%M') AS two_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND od.over_status = 3
                   ), 0) AS two_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH, '%M') AS three_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND od.over_status = 3
                   ), 0) AS three_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 4 MONTH, '%M') AS four_months_ago,
            IFNULL((SELECT SUM(ot.over_tamt)
                    FROM over_orders od
                    LEFT JOIN over_total ot ON ot.over_id = od.over_id
                    WHERE YEAR(od.over_date) = YEAR(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND MONTH(od.over_date) = MONTH(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND od.over_status = 3
                   ), 0) AS four_months_ago_sales";

$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
    while ($rows = $result1->fetch_assoc()) {
        // OTC month names & sales
        $twoMonths1          = $rows['two_months_ago'];
        $threeMonths1        = $rows['three_months_ago'];
        $fourMonths1         = $rows['four_months_ago'];
        $previous_month1     = $rows['previous_month'];
        $current_month1      = $rows['current_month'];

        $last_two_months_sales1   = $rows['two_months_ago_sales'];
        $last_three_months_sales1 = $rows['three_months_ago_sales'];
        $last_four_months_sales1  = $rows['four_months_ago_sales'];
        $previous_sales1          = $rows['previous_month_sales'];
        $current_sales1           = $rows['current_month_sales'];
    }
}

// Query for regular sales
$sql = "SELECT 
            DATE_FORMAT(CURRENT_DATE(), '%M') AS current_month,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE())
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE())
                    AND o.status = 3
                   ), 0) AS current_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH, '%M') AS previous_month,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND o.status = 3
                   ), 0) AS previous_month_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 2 MONTH, '%M') AS two_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 2 MONTH)
                    AND o.status = 3
                   ), 0) AS two_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH, '%M') AS three_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 3 MONTH)
                    AND o.status = 3
                   ), 0) AS three_months_ago_sales,
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 4 MONTH, '%M') AS four_months_ago,
            IFNULL((SELECT SUM(ot.total_amt)
                    FROM orders o
                    LEFT JOIN order_total ot ON ot.order_id = o.order_id
                    WHERE YEAR(o.order_date) = YEAR(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND MONTH(o.order_date) = MONTH(CURRENT_DATE() - INTERVAL 4 MONTH)
                    AND o.status = 3
                   ), 0) AS four_months_ago_sales";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Regular month names & sales
        $twoMonths           = $row['two_months_ago'];
        $threeMonths         = $row['three_months_ago'];
        $fourMonths          = $row['four_months_ago'];
        $previous_month      = $row['previous_month'];
        $current_month       = $row['current_month'];

        $last_two_months_sales   = $row['two_months_ago_sales'];
        $last_three_months_sales = $row['three_months_ago_sales'];
        $last_four_months_sales  = $row['four_months_ago_sales'];
        $previous_sales          = $row['previous_month_sales'];
        $current_sales           = $row['current_month_sales'];
    }

    // ——— Add combined OTC + regular for forecast calculation ———
    $combined_current_sales  = $current_sales  + $current_sales1;
    $combined_previous_sales = $previous_sales + $previous_sales1;

    if ($combined_previous_sales == 0) {
        $growth_rate = 0;
    } else {
        $growth_rate = (($combined_current_sales - $combined_previous_sales)
                        / $combined_previous_sales) * 100;
    }

    if ($growth_rate == 0) {
        $forecast_move = 0;
    } else {
        $forecast_move = $combined_current_sales * ($growth_rate / 100);
    }

    if ($forecast_move == 0) {
        $forecast_sales = 0;
    } else {
        $forecast_sales = $combined_current_sales + $forecast_move;
    }
    // — end added block —

    // Prepare data for output
    $data = [
        'four_months' => [
            'month' => $fourMonths,
            'sales' => $last_four_months_sales  + $last_four_months_sales1
        ],
        'three_months' => [
            'month' => $threeMonths,
            'sales' => $last_three_months_sales + $last_three_months_sales1
        ],
        'two_months' => [
            'month' => $twoMonths,
            'sales' => $last_two_months_sales   + $last_two_months_sales1
        ],
        'previous_month' => [
            'month' => $previous_month,
            'sales' => $previous_sales  + $previous_sales1
        ],
        'current_month' => [
            'month' => $current_month,
            'sales' => $current_sales   + $current_sales1
        ],
        'forecast_sales' => $forecast_sales,
        'growth_rate'    => $growth_rate,
        'forecast_move'  => $forecast_move
    ];

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'No data found']);
}
