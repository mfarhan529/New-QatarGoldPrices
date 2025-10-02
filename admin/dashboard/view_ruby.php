<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}
include '../includes/db.php';

$currency_display = [
    "QAR" => "QAR",
    "USD" => "$",
    "INR" => "₹"
];

function getRubyByCurrency($conn, $currency_symbol) {
    $sql = "
        SELECT r.id, r.type, r.Price_Range, r.created_at,
               w.unit AS weight_unit, c.Symbol
        FROM ruby_prices r
        INNER JOIN (
            SELECT type, weight_id, currency_id, MAX(created_at) AS latest_date
            FROM ruby_prices
            GROUP BY type, weight_id, currency_id
        ) latest 
        ON r.type = latest.type 
           AND r.weight_id = latest.weight_id 
           AND r.currency_id = latest.currency_id 
           AND r.created_at = latest.latest_date
        LEFT JOIN currencies c ON r.currency_id = c.id
        LEFT JOIN weight w ON r.weight_id = w.id
        WHERE c.Symbol = ?
        ORDER BY r.created_at ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currency_symbol);
    $stmt->execute();
    return $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Ruby Rates</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
    .table-wrapper { margin-left: 400px; padding: 40px 20px; width: 1000px; }
    .table-container { background: #fff; padding: 25px; border-radius: 14px; margin-bottom: 40px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    h2 { text-align: center; margin-bottom: 20px; color: #1e3a8a; font-size: 22px; font-weight: 700; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead { background: #1e3a8a; color: #fff; }
    th, td { padding: 12px 14px; border: 1px solid #ddd; text-align: center; }
    tbody tr:nth-child(even) { background: #f9f9f9; }
    tbody tr:hover { background: #eef2ff; }
  </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="table-wrapper">

  <?php foreach (["QAR","INR","USD"] as $currency_symbol): ?>
    <div class="table-container">
      <h2> Ruby Prices (<?= $currency_symbol ?>)</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Gemstone</th>
            <th>Weight</th>
            <th>Price Range</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $result = getRubyByCurrency($conn, $currency_symbol);
          if ($result && $result->num_rows > 0): 
              while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= date("d-M-Y", strtotime($row['created_at'])) ?></td>
                  <td><?= htmlspecialchars($row['type']) ?></td>
                  <td><?= htmlspecialchars($row['weight_unit']) ?></td>
                  <td><?= $currency_display[$currency_symbol] . ' ' . $row['Price_Range'] ?></td>
                </tr>
              <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="4">❌ No records found for <?= $currency_symbol ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>

</div>
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
