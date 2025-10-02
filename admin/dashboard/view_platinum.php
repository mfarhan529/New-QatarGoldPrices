<?php
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

// ✅ Fetch all currencies
$currencies = [];
// currency display
$currency_display = [
    "QAR" => "QAR",
    "USD" => "$",
    "INR" => "₹"
];
// Fetch all dropdown data
$currency_sql = "SELECT id, Symbol FROM currencies ORDER BY id ASC";
$currency_result = $conn->query($currency_sql);


$weight_sql = "SELECT id, unit FROM weight ORDER BY id ASC";
$weight_result = $conn->query($weight_sql);

if ($currency_result && $currency_result->num_rows > 0) {
    while ($c = $currency_result->fetch_assoc()) {
        $currencies[$c['id']] = $c['Symbol']; // map id => Symbol
    }
}

   // Today’s Silver Prices (latest per weight+currency in last 24 hours)
    $today_sql = "SELECT p1.id AS platinum_id, p1.Prices, p1.created_at,
                         w.unit AS weight_unit, 
                         c.Symbol, c.id AS currency_id
                  FROM platinum_prices p1
                  INNER JOIN (
                      SELECT weight_id, currency_id, MAX(created_at) as max_date
                      FROM platinum_prices
                      WHERE created_at >= NOW() - INTERVAL 24 HOUR
                      GROUP BY weight_id, currency_id
                  ) p2
                  ON p1.weight_id = p2.weight_id
                  AND p1.currency_id = p2.currency_id
                  AND p1.created_at = p2.max_date
                  LEFT JOIN currencies c ON p1.currency_id = c.id
                  LEFT JOIN weight w ON p1.weight_id = w.id
                  ORDER BY w.id ASC, p1.currency_id ASC";
    $today_result = $conn->query($today_sql);

    // history
  $history_sql = "SELECT p.id AS platinum_id, p.weight_id, p.currency_id, p.Prices, p.created_at, 
                           c.Symbol, w.unit AS weight_unit
                    FROM platinum_prices p
                    LEFT JOIN currencies c ON p.currency_id = c.id
                    LEFT JOIN weight w ON p.weight_id = w.id
                    WHERE c.Symbol = 'QAR'
                    ORDER BY p.created_at DESC, w.id ASC";
    $history_result = $conn->query($history_sql);


// history



// ✅ Group result
function groupByDateWeightCurrency($today_result) {
    $today_data = [];
   if ($today_result && $today_result->num_rows > 0) {
        while ($row = $today_result->fetch_assoc()) {
            $date   = date("d-M-Y", strtotime($row['created_at']));
            $weight = $row['weight_unit'];
            $key = $date . "|" . $weight;
            if (!isset($today_data[$key])) {
                $today_data[$key] = [
                    'id'     => $row['platinum_id'],
                    'date'   => $date,
                    'weight' => $weight,
                    'prices' => []
                ];
            }
            $today_data[$key]['prices'][$row['currency_id']] = $row['Prices'];
        }
    }
    return $today_data;
}

$today_data = groupByDateWeightCurrency($today_result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Platinum Rates </title>


</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="table-wrapper">

  <!-- ✅ Success message -->
  <?php if (isset($_GET['success'])): ?>
    <p class="success-msg" id="AddMsg">✅ Gold rate added successfully!</p>
    
  <script>
    // Auto-hide after 3 seconds
    setTimeout(() => {
      const msg = document.getElementById("AddMsg");
      if (msg) {
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500); // remove completely after fade-out
      }
    }, 3000);
  </script>
  <?php endif; ?>
<!-- ✅ Today's Gold Prices -->

<div class="table-container">
  <h2>📊 Today’s Platinum Prices</h2>
  <table>
    <thead>
      <tr>
        <th>Date</th>
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
            
            <td><?= htmlspecialchars($info['weight']) ?></td>
           <?php foreach ($currencies as $cid => $code): ?>
  <?php $symbol = $currency_display[$code] ?? $code; ?>
  <td>
    <?= isset($info['prices'][$cid]) ? $symbol . ' ' . number_format($info['prices'][$cid], 2) : '-' ?>
  </td>
<?php endforeach; ?>

           <td>
  <a href="delete_platinum.php?id=<?= $info['id'] ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this record?');">🗑️</a>
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


// ✅ Collect history rows

  $history_rows = [];
    if ($history_result && $history_result->num_rows > 0) {
        while ($r = $history_result->fetch_assoc()) {
            $history_rows[] = $r;
        }
    }

// ✅ Group history by date + purities
function groupHistoryByDate($history_rows) {
   // Group history by date
    $history_data = [];
    foreach ($history_rows as $row) {
        $date = date("d-M-Y", strtotime($row['created_at']));
        if (!isset($history_data[$date])) {
            $history_data[$date] = [];
        }
        $history_data[$date][$row['weight_unit']] = $row['Prices'];
    }
    return $history_data;
}

$history_data = groupHistoryByDate($history_rows);
?>
<!-- 📜 History Gold Prices -->
<div class="table-container">
  <h2>📜 History Platinum Prices</h2>
  <table class="history-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>1 Gram</th>
        <th>1 Tola</th>
        <th>1 Ounce</th>
    

      </tr>
    </thead>
    <tbody>
      <?php if (!empty($history_data)): ?>
        <?php foreach ($history_data as $date => $weights): ?>
          <tr>
            <td><?= htmlspecialchars($date) ?></td>
                   <td><?= isset($weights['1 Gram']) ? 'QAR ' . number_format($weights['1 Gram'], 2) : '-' ?></td>
                <td><?= isset($weights['1 Tola']) ? 'QAR ' . number_format($weights['1 Tola'], 2) : '-' ?></td>
                <td><?= isset($weights['1 Ounce']) ? 'QAR ' . number_format($weights['1 Ounce'], 2) : '-' ?></td>
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
