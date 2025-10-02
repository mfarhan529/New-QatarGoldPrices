<?php
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';


// delete 



  


// ✅ Delete record if ID is provided
 

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>

  <div id="alert" style="
        background: #4CAF50;
        color: white;
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
        ">
        ✅ Data deleted successfully
    </div>
      <script>
        // Auto-hide after 3 seconds
        setTimeout(function() {
            var alert = document.getElementById("alert");
            if (alert) {
                alert.style.display = "none";
            }
        }, 3000);
    </script>
<?php endif; ?>
<?php

// ✅ Fetch all currencies
$currencies = [];
$currency_display = [
    "QAR" => "QAR",
    "USD" => "$",
    "INR" => "₹"
];
// Fetch all dropdown data
$currency_sql = "SELECT id, Symbol FROM currencies ORDER BY id ASC";
$currency_result = $conn->query($currency_sql);

$purity_sql = "SELECT id, name FROM purities ORDER BY id ASC";
$purity_result = $conn->query($purity_sql);

$weight_sql = "SELECT id, unit FROM weight ORDER BY id ASC";
$weight_result = $conn->query($weight_sql);

if ($currency_result && $currency_result->num_rows > 0) {
    while ($c = $currency_result->fetch_assoc()) {
        $currencies[$c['id']] = $c['Symbol']; // map id => Symbol
    }
}

// ✅ Today's Gold Prices (last 24 hours)
$today_sql = "SELECT g1.id AS gold_id, g1.Prices, g1.created_at,
                     p.name AS purity_name, 
                     w.unit AS weight_unit, 
                     c.symbol, c.id AS currency_id
              FROM gold_prices g1
              INNER JOIN (
                  SELECT purity_id, weight_id, currency_id, MAX(created_at) as max_date
                  FROM gold_prices
                  WHERE created_at >= NOW() - INTERVAL 24 HOUR
                  GROUP BY purity_id, weight_id, currency_id
              ) g2
              ON g1.purity_id = g2.purity_id
              AND g1.weight_id = g2.weight_id
              AND g1.currency_id = g2.currency_id
              AND g1.created_at = g2.max_date
              LEFT JOIN currencies c ON g1.currency_id = c.id
              LEFT JOIN purities p ON g1.purity_id = p.id
              LEFT JOIN weight w ON g1.weight_id = w.id
              ORDER BY p.id ASC, w.id ASC, g1.currency_id ASC";


$today_result = $conn->query($today_sql);

// ✅ History Gold Prices (older than 24 hours, only QAR)
// ✅ History Gold Prices (older than 24 hours, only QAR, per gram)
$history_sql = "SELECT g.id AS gold_id, g.currency_id, g.purity_id, g.weight_id, g.Prices, g.created_at, 
                       c.Symbol, p.name AS purity_name, w.unit AS weight_unit
                FROM gold_prices g
                LEFT JOIN currencies c ON g.currency_id = c.id
                LEFT JOIN purities p ON g.purity_id = p.id
                LEFT JOIN weight w ON g.weight_id = w.id
                WHERE c.Symbol = 'QAR'          -- only QAR
                  AND w.unit = '1 Gram'        -- ✅ only 1 Gram
                ORDER BY g.created_at DESC, p.id ASC";  // ✅ latest date first


$history_result = $conn->query($history_sql);


// history



// ✅ Group result
function groupByDatePurityWeightCurrency($result) {
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date   = date("d-M-Y", strtotime($row['created_at'])); // format 23-Sep-2025
            $purity = $row['purity_name'];
            $weight = $row['weight_unit'];

            $key = $date . '|' . $purity . '|' . $weight;

            if (!isset($data[$key])) {
                $data[$key] = [
                    'id'     => $row['gold_id'],
                    'date'   => $date,
                    'purity' => $purity,
                    'weight' => $weight,
                    'prices' => []
                ];
            }
            $data[$key]['prices'][$row['currency_id']] = $row['Prices'];
        }
    }
    return $data;
}

$today_data = groupByDatePurityWeightCurrency($today_result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Gold Rates </title>
  <style>
a.purity-link {
    color: #4c9fbbff; /* light blue */
    text-decoration: none;
}
a.purity-link:hover {
    color: black;
}
</style>

</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="table-wrapper">

  <!-- ✅ Success message -->
  <?php if (isset($_GET['success'])): ?>
    <p class="success-msg">✅ Gold rate added successfully!</p>
  <?php endif; ?>
<!-- ✅ Today's Gold Prices -->
 <?php
// ✅ Map purity names to slugs
$purity_links = [
    "24K Gold" => "24-carat-gold",
    "22K Gold" => "22-carat-gold",
    "21K Gold" => "21-carat-gold",
    "18K Gold" => "18-carat-gold",
    "10K Gold" => "10-carat-gold"
];
?>
<div class="table-container">
  <h2>📊 Today’s Gold Prices</h2>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Purity</th>
        <th>Weight Unit</th>
        <?php foreach ($currencies as $symbol): ?>
          <th><?= htmlspecialchars($symbol) ?></th>
        <?php endforeach; ?>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($today_data)): ?>
        <?php foreach ($today_data as $info): ?>
          <tr>
            <td><?= htmlspecialchars($info['date']) ?></td>
            <td>
              <?php 
                $purity = $info['purity'];
                $slug   = isset($purity_links[$purity]) ? $purity_links[$purity] : strtolower(str_replace(" ", "-", $purity));
              ?>
              <a href="http://localhost/qatar/<?= $slug ?>">
                <?= htmlspecialchars($purity) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($info['weight']) ?></td>
            <?php foreach ($currencies as $cid => $code): ?>
  <?php $symbol = $currency_display[$code] ?? $code; ?>
  <td>
    <?= isset($info['prices'][$cid]) ? $symbol . ' ' . number_format($info['prices'][$cid], 2) : '-' ?>
  </td>
<?php endforeach; ?>
    <td>
  <a href="delete_gold.php?id=<?= $info['id'] ?>" 
     title="Delete" 
     onclick="return confirm('Are you sure you want to delete this record?');">🗑️</a>
</td>



          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="<?= count($currencies) + 4 ?>" style="text-align:center;">❌ No records found for Today</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php 
$all_purities = ["24K Gold", "22K Gold", "21K Gold", "18K Gold","10K Gold"];

// ✅ Collect history rows
$history_rows = [];
if ($history_result && $history_result->num_rows > 0) {
    while ($r = $history_result->fetch_assoc()) {
        // ✅ Convert to per gram price (assuming Prices is for full unit)
        $per_gram = 0;
        if (is_numeric($r['Prices']) && is_numeric($r['weight_unit']) && $r['weight_unit'] > 0) {
            $per_gram = $r['Prices'] / $r['weight_unit'];
        } else {
            $per_gram = $r['Prices']; // fallback if already per gram
        }
        $r['per_gram'] = $per_gram;
        $history_rows[] = $r;
    }
}

// ✅ Group history by date + purities
function groupHistoryByDatePurity($rows) {
    $data = [];
    foreach ($rows as $row) {
        $date   = date("d-M-Y", strtotime($row['created_at'])); // format: 23-Sep-2025
        $purity = $row['purity_name'];
        $price  = $row['per_gram'];

        if (!isset($data[$date])) {
            $data[$date] = [];
        }
        $data[$date][$purity] = $price;
    }
    return $data;
}

$history_data = groupHistoryByDatePurity($history_rows);
?>
<!-- 📜 History Gold Prices -->
<div class="table-container">
  <h2>📜 History Gold Prices (QAR per gram)</h2>
  <table class="history-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>24K Gold</th>
        <th>22K Gold</th>
        <th>21K Gold</th>
        <th>18K Gold</th>
        <th>10K Gold</th>
    

      </tr>
    </thead>
    <tbody>
      <?php if (!empty($history_data)): ?>
        <?php foreach ($history_data as $date => $purities): ?>
          <tr>
            <td><?= htmlspecialchars($date) ?></td>
            <?php foreach ($all_purities as $p): ?>
              <td>
                <?= isset($purities[$p]) ? 'QAR ' . number_format($purities[$p], 2) : '-' ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center;">❌ No historical data found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<style>
  a.purity-link {
  color: #6cb0c7ff;  /* light blue */
  text-decoration: none;
  font-weight: 500;
}

a.purity-link:hover {
  color: #000; /* black on hover */
}

  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
  .table-wrapper { margin-left: 400px; padding: 40px 20px; width: 1000px; }
  .table-container { background: #fff; padding: 25px; border-radius: 14px; margin-bottom: 40px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
  h2 { text-align: center; margin-bottom: 20px; color: #1e3a8a; font-size: 24px; font-weight: 700; }
  .success-msg { text-align: center; background: #d1fae5; color: #065f46; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-weight: 600; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #1e3a8a; color: #fff; }
  th, td { padding: 12px 14px; border: 1px solid #ddd; text-align: center; }
  tbody tr:nth-child(even) { background: #f9f9f9; }
  tbody tr:hover { background: #eef2ff; }
  a { text-decoration: none; font-size: 18px; color: #1e3a8a; }
  a:hover { color: #0d1a3a; }
</style>

<script>
document.querySelectorAll('.dropdown-toggle').forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    this.parentElement.classList.toggle('open');
  });
});
</script>
</body>
</html>
