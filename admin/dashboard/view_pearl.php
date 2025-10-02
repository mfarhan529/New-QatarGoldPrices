<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../"); // Redirect to login if not logged in
    exit();
}

include '../includes/db.php';
?>




  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> -->
  </head>
  <body>

   
    <?php


$currency_display = [
    "QAR" => "QAR",
    "USD" => "$",
    "INR" => "₹"
];

?>
 



<?php
function getPearlByCurrency($conn, $currency_symbol) {
    $sql = "
        SELECT pe.id, pe.type, pe.Length, pe.Price_Range, pe.created_at
        FROM pearl_prices pe
        INNER JOIN (
            SELECT type, Length, currency_id, MAX(created_at) AS latest_date
            FROM pearl_prices
            GROUP BY type, Length, currency_id
        ) latest 
        ON pe.type = latest.type  
           AND pe.Length = latest.Length 
           AND pe.currency_id = latest.currency_id 
           AND pe.created_at = latest.latest_date
        LEFT JOIN currencies c ON pe.currency_id = c.id
        WHERE c.Symbol = ?
        ORDER BY pe.created_at ASC
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pearl Table</title>
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
  <?php  
      include '../includes/sidebar.php';
  ?>
<div class="table-wrapper">

  <?php foreach (["QAR","INR","USD"] as $currency_symbol): ?>
    <div class="table-container">
      <h2> Pearl Prices (<?= $currency_symbol ?>)</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Gemstone</th>
            <th>Length</th>
            <th>Price Range</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $result = getPearlByCurrency($conn, $currency_symbol);
          if ($result && $result->num_rows > 0): 
              while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= date("d-M-Y", strtotime($row['created_at'])) ?></td>
                  <td><?= htmlspecialchars($row['type']) ?></td>
                <td><?= htmlspecialchars($row['Length']) ?></td>

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

</body>


     <script>
document.querySelectorAll('.dropdown-toggle').forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    this.parentElement.classList.toggle('open');
  });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const alerts = document.querySelectorAll("#successAlert, #deleteAlert");
    alerts.forEach(alertBox => {
        setTimeout(() => {
            alertBox.style.animation = "fadeOut 0.8s forwards";
            setTimeout(() => alertBox.remove(), 800);
        }, 3000); // 3 seconds
    });
});
</script>




  </body>

  </html>