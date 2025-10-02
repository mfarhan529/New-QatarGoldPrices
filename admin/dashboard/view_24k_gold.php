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
$currency_sql = "SELECT id, Symbol FROM currencies ORDER BY id ASC";
$currency_result = $conn->query($currency_sql);

if ($currency_result && $currency_result->num_rows > 0) {
    while ($c = $currency_result->fetch_assoc()) {
        $currencies[$c['id']] = $c['Symbol']; // map id => Symbol
    }
}
// ✅ Today’s Latest 24K Gold Prices (All Weights, All Currencies)
$today_sql = "
    SELECT g.id AS gold_id, g.currency_id, g.purity_id, g.weight_id, g.Prices, g.created_at, 
           c.Symbol, p.name AS purity_name, w.unit AS weight_unit
    FROM gold_prices g
    INNER JOIN (
        SELECT weight_id, currency_id, MAX(created_at) AS max_date
        FROM gold_prices
        WHERE purity_id = 1      -- ✅ Only 24K Gold
        GROUP BY weight_id, currency_id
    ) latest
    ON g.weight_id   = latest.weight_id
    AND g.currency_id = latest.currency_id
    AND g.created_at = latest.max_date
    LEFT JOIN currencies c ON g.currency_id = c.id
    LEFT JOIN purities p ON g.purity_id = p.id
    LEFT JOIN weight w ON g.weight_id = w.id
    ORDER BY w.id ASC, g.currency_id ASC
";

$today_result = $conn->query($today_sql);



        $history_sql = "SELECT g.id AS gold_id, g.currency_id, g.purity_id, g.weight_id, g.Prices, g.created_at, 
                               c.Symbol, p.name AS purity_name, w.unit AS weight_unit
                        FROM gold_prices g
                        LEFT JOIN currencies c ON g.currency_id = c.id
                        LEFT JOIN purities p ON g.purity_id = p.id
                        LEFT JOIN weight w ON g.weight_id = w.id
                        WHERE g.purity_id = 1  /* Only 24K Gold */
                          AND c.Symbol = 'QAR'
                        ORDER BY g.created_at DESC, w.id ASC";

        $history_result = $conn->query($history_sql);

        // ✅ Group History by Date
        $history_data = [];
        if ($history_result && $history_result->num_rows > 0) {
            while ($row = $history_result->fetch_assoc()) {
                $date   = date("d-M-Y", strtotime($row['created_at']));
                $weight = $row['weight_unit'];

                if (!isset($history_data[$date])) {
                    $history_data[$date] = [
                        'date'   => $date,
                        'prices' => []
                    ];
                }
                $history_data[$date]['prices'][$weight] = $row['Prices'];
            }
        }
// today function  
// ✅ Function to group by weight/currency
function groupByWeightCurrency($result) {
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $weight = $row['weight_unit'];
            if (!isset($data[$weight])) {
                $data[$weight] = [
                    'id'     => $row['gold_id'],
                    'weight' => $weight,
                    'prices' => []
                ];
            }
            $data[$weight]['prices'][$row['currency_id']] = $row['Prices'];
        }
    }
    return $data;
}

$today_data = groupByWeightCurrency($today_result);

// history function   


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View 24k Gold Rates</title>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="table-wrapper">

  <!-- ✅ Success message -->
  <?php if (isset($_GET['success'])): ?>
    <p class="success-msg">✅ 24k Gold rate added successfully!</p>
  <?php endif; ?><div class="table-container">
  <h2>📊 Today’s Latest 24K Gold Prices (1 Gram)</h2>
  <table>
    <thead>
      <tr>
        <th>Weight</th>
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
            <td><?= htmlspecialchars($info['weight']) ?></td>
             <?php foreach ($currencies as $cid => $code): ?>
  <?php $symbol = $currency_display[$code] ?? $code; ?>
  <td>
    <?= isset($info['prices'][$cid]) ? $symbol . ' ' . number_format($info['prices'][$cid], 2) : '-' ?>
  </td>
<?php endforeach; ?>
            <td><a href="edit_24k_gold.php?id=<?= $info['id'] ?>" title="Edit">✏️</a></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="<?= count($currencies) + 2 ?>" style="text-align:center;">❌ No recent record found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
     <div class="table-container">
        
  <h2>📜 History 24K Gold Prices</h2>
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
                <?php foreach ($history_data as $info): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($info['date']); ?></td>
                    <td><?php echo isset($info['prices']['1 Gram']) ? 'QAR '.number_format($info['prices']['1 Gram'], 2) : '-'; ?></td>
                    <td><?php echo isset($info['prices']['1 Tola']) ? 'QAR '.number_format($info['prices']['1 Tola'], 2) : '-'; ?></td>
                    <td><?php echo isset($info['prices']['1 Ounce']) ? 'QAR '.number_format($info['prices']['1 Ounce'], 2) : '-'; ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">❌ No historical data found</td></tr>
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
