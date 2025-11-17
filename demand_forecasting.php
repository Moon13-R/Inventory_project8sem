<?php
$page_title = 'Demand Forecasting';
require_once('includes/load.php');
page_require_level(3);

// Fetch all products
$all_products = find_all('products');

// Handle POST
$selected_product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : 'simple_average';
$ma_window = isset($_POST['ma_window']) ? max(1, (int)$_POST['ma_window']) : 7;
$alpha = isset($_POST['alpha']) ? floatval($_POST['alpha']) : 0.3;
$forecast_period_type = isset($_POST['forecast_period_type']) ? $_POST['forecast_period_type'] : 'weeks';
$forecast_period_value = isset($_POST['forecast_period_value']) ? (int)$_POST['forecast_period_value'] : 4;
$custom_start_date = isset($_POST['custom_start_date']) ? $_POST['custom_start_date'] : '';

$sales_data = [];
$daily_forecast = [];
$average_daily_sales = 0;
$forecast_next_7_days = 0;
$product = null;

// Fetch product and sales data
if ($selected_product_id) {
    $product = find_by_id('products', $selected_product_id);

    if ($product) {
        // Determine start date based on user selection
        if ($forecast_period_type === 'weeks') {
            $history_days = $forecast_period_value * 7;
            $start_date_sql = "DATE_SUB(CURDATE(), INTERVAL $history_days DAY)";
        } elseif ($forecast_period_type === 'months') {
            $history_days = $forecast_period_value * 30;
            $start_date_sql = "DATE_SUB(CURDATE(), INTERVAL $history_days DAY)";
        } elseif ($forecast_period_type === 'custom' && $custom_start_date) {
            $start_date_sql = "'$custom_start_date'";
        } else {
            $start_date_sql = "DATE_SUB(CURDATE(), INTERVAL 90 DAY)";
        }

        // Fetch sales
        $sql = "SELECT date, SUM(qty) AS total_qty
                FROM sales
                WHERE product_id='{$selected_product_id}' 
                AND date >= $start_date_sql
                GROUP BY date
                ORDER BY date ASC";
        $result = $db->query($sql);
        while ($row = $db->fetch_assoc($result)) {
            $sales_data[$row['date']] = (int)$row['total_qty'];
        }

        // Fill missing dates with 0
        $start = strtotime($forecast_period_type === 'custom' ? $custom_start_date : date('Y-m-d', strtotime("-".($history_days-1)." days")));
        $end = strtotime(date('Y-m-d'));
        $timeline = [];
        for ($t = $start; $t <= $end; $t += 86400) {
            $date = date('Y-m-d', $t);
            $timeline[$date] = $sales_data[$date] ?? 0;
        }

        $history_values = array_values($timeline);

        // Forecast algorithm
        switch ($algorithm) {
            case 'moving_average':
                $window = min($ma_window, count($history_values));
                $average_daily_sales = $window ? array_sum(array_slice($history_values, -$window)) / $window : 0;
                break;

            case 'exponential_smoothing':
                $alpha = max(0.01, min(0.99, $alpha));
                $s = null;
                foreach ($history_values as $x) {
                    $s = $s === null ? $x : $alpha * $x + (1 - $alpha) * $s;
                }
                $average_daily_sales = $s ?? 0;
                break;

            case 'simple_average':
            default:
                $take = min(7, count($history_values));
                $average_daily_sales = $take ? array_sum(array_slice($history_values, -$take)) / $take : 0;
                break;
        }

        // Generate next 7 days forecast
        for ($i = 1; $i <= 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i day"));
            $daily_forecast[$date] = round($average_daily_sales);
        }

        $forecast_next_7_days = array_sum($daily_forecast);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php echo display_msg($msg); ?>

        <div class="card">
            <h3 class="card-header">Demand Forecasting</h3>
            <div class="card-body">
                <form method="post" class="form-inline mb-3">
                    <select name="product_id" class="form-control mr-2" required onchange="this.form.submit()">
                        <option value="">-- Select Product --</option>
                        <?php foreach ($all_products as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php if($selected_product_id == $p['id']) echo 'selected'; ?>>
                            <?php echo $p['name'] . ' (' . (!empty($p['upc']) ? $p['upc'] : 'N/A') . ', ' . (!empty($p['value_size']) ? $p['value_size'] : 'N/A') . ')'; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="algorithm" class="form-control mr-2">
                        <option value="simple_average" <?php if($algorithm=='simple_average') echo 'selected'; ?>>Simple Average</option>
                        <option value="moving_average" <?php if($algorithm=='moving_average') echo 'selected'; ?>>Moving Average</option>
                        <option value="exponential_smoothing" <?php if($algorithm=='exponential_smoothing') echo 'selected'; ?>>Exponential Smoothing</option>
                    </select>

                    <input type="number" name="ma_window" value="<?php echo $ma_window; ?>" class="form-control mr-2" min="1" placeholder="MA Window">
                    
                    <input type="number" step="0.01" name="alpha" value="<?php echo $alpha; ?>" class="form-control mr-2" min="0.01" max="0.99" placeholder="Alpha">

                    <!-- Forecast period toggle -->
                    <select name="forecast_period_type" class="form-control mr-2">
                        <option value="weeks" <?php if($forecast_period_type=='weeks') echo 'selected'; ?>>Weeks</option>
                        <option value="months" <?php if($forecast_period_type=='months') echo 'selected'; ?>>Months</option>
                        <option value="custom" <?php if($forecast_period_type=='custom') echo 'selected'; ?>>Custom Date</option>
                    </select>

                    <input type="number" name="forecast_period_value" value="<?php echo $forecast_period_value; ?>" class="form-control mr-2" min="1" placeholder="Value">
                    <input type="date" name="custom_start_date" value="<?php echo $custom_start_date; ?>" class="form-control mr-2">

                    <button type="submit" class="btn btn-primary">Run Forecast</button>
                </form>

                <?php if($selected_product_id && $product): ?>
                    <hr>
                    <h5>Product Details</h5>
                    <p><strong>Name:</strong> <?php echo $product['name']; ?> | <strong>UPC:</strong> <?php echo $product['upc']; ?> | <strong>Size:</strong> <?php echo $product['value_size']; ?> | <strong>Stock:</strong> <?php echo $product['quantity']; ?></p>
                    <?php if($product['photo']): ?>
                        <img src="uploads/products/<?php echo $product['photo']; ?>" style="max-width:150px; border:1px solid #ccc;">
                    <?php endif; ?>

                    <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#forecastChartModal">
                        View Sales & Forecast Chart
                    </button>

                    <h5>7-Day Forecast</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Forecasted Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($daily_forecast as $date=>$qty): ?>
                                <tr>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $qty; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Forecast Chart Modal -->
<div class="modal fade" id="forecastChartModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sales & Forecast Chart</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <canvas id="forecastChart" style="height:400px;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if($sales_data): ?>
const ctx = document.getElementById('forecastChart').getContext('2d');
const forecastChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            <?php foreach($timeline as $date=>$qty) echo "'$date',"; ?>
        ],
        datasets: [
            {
                label: 'Actual Sales',
                data: [
                    <?php foreach($timeline as $date=>$qty) echo $qty.","; ?>
                ],
                borderColor: 'rgba(75,192,192,1)',
                backgroundColor: 'rgba(75,192,192,0.2)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Forecast Next 7 Days',
                data: (function(){
                    let data = [];
                    <?php 
                        $last_date = date('Y-m-d', strtotime("-1 day"));
                        foreach($timeline as $d=>$qty) echo "data.push(null);"; // leave gaps
                    ?>
                    <?php foreach($daily_forecast as $d=>$q) echo "data.push($q);"; ?>
                    return data;
                })(),
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,0.2)',
                fill: true,
                tension: 0.3
            }
        ]
    },
    options: {
        scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'Quantity' }, beginAtZero: true }
        }
    }
});
<?php endif; ?>
</script>

<?php include_once('layouts/footer.php'); ?>
