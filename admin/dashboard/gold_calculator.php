<?php
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

// ✅ Purity fixed as 24K Gold
$purity_id = 1; // 👈 change this to your actual purity_id for 24K Gold

// ✅ Fetch weight units (only Tola, Gram, Ounce)
$weights = [];
$w_query = $conn->query("SELECT id, unit FROM weight WHERE unit IN ('1 Tola','1 Gram','1 Ounce')");
while ($row = $w_query->fetch_assoc()) {
    $weights[$row['id']] = $row['unit'];
}

$result_msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $weight_id = intval($_POST['weight_id']);
    $user_weight = floatval($_POST['weight']);

    if ($user_weight <= 0) {
        $result_msg = "<p style='color:red;font-weight:bold;'>❌ Enter a valid weight greater than 0.</p>";
    } else {
        // ✅ Fetch latest prices for QAR, USD, INR
        $currencies = ['QAR' => 1, 'USD' => 2, 'INR' => 3];
        $results = [];

        foreach ($currencies as $symbol => $currency_id) {
            $stmt = $conn->prepare("
                SELECT Prices 
                FROM gold_prices 
                WHERE purity_id = ? AND weight_id = ? AND currency_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1
            ");
            $stmt->bind_param("iii", $purity_id, $weight_id, $currency_id);
            $stmt->execute();
            $stmt->bind_result($price);

            if ($stmt->fetch()) {
                $total_price = $price * $user_weight;
                $results[$symbol] = number_format($total_price, 2);
            }
            $stmt->close();
        }

   if (!empty($results)) {
    $result_msg = "<div class='result-box'>
        <h3>💰 Calculated Price</h3>
        <ul>";
    foreach ($results as $symbol => $value) {
        // Remove leading "1 " from unit name
        $unit_clean = preg_replace('/^1\s*/', '', $weights[$weight_id]);

        $result_msg .= "<li>The price of {$user_weight} {$unit_clean} of 24K Gold in {$symbol} is <strong>{$value}</strong></li>";
    }
    $result_msg .= "</ul></div>";
} else {
    $result_msg = "<p style='color:red;font-weight:bold;'>❌ Latest price not available in database.</p>";
}


    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gold Rate Calculator</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }
    .form-wrapper {
      margin-left: 400px;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 800px;
    }
    .form-container {
      width: 100%;
      background: #fff;
      padding: 35px 40px;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      text-align: center;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #000;
      font-size: 26px;
      font-weight: 700;
    }
    .form-group {
      margin-bottom: 20px;
      text-align: center;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
      color: #000;
      font-size: 16px;
    }
    input, select {
      width: 60%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      background: #fafafa;
      margin: 0 auto;
      display: block;
      text-align: center;
    }
    input:focus, select:focus {
      border-color: #8b0000;
      background: #fff;
      box-shadow: 0 0 8px rgba(139,0,0,0.2);
      outline: none;
    }
    button {
      margin-top: 25px;
      padding: 14px;
      background: darkred;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 60%;
      font-size: 17px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    button:hover {
      background: #a40000;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .result-box {
      margin-top: 30px;
      padding: 20px;
      border-radius: 10px;
      background: #f9f9f9;
      border: 1px solid #ddd;
      text-align: left;
    }
    .result-box h3 {
      margin-bottom: 15px;
      font-size: 20px;
      color: #333;
    }
    .result-box ul {
      list-style: none;
      padding: 0;
    }
    .result-box li {
      font-size: 18px;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="form-wrapper">
  <div class="form-container">
    <h2>💰 Calculate Gold Rate</h2>

    <form method="POST" action="">
      <div class="form-group">
        <label>Select Unit:</label>
        <select name="weight_id" required>
          <option value="">-- Select Unit --</option>
          <?php foreach ($weights as $id => $unit): ?>
            <option value="<?= $id ?>"><?= htmlspecialchars($unit) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Enter Weight:</label>
        <input type="number" step="0.01" name="weight" placeholder="Enter weight..." required>
      </div>

      <button type="submit">Calculate</button>
    </form>

    <!-- ✅ Result -->
    <?= $result_msg ?>

  </div>
</div>

</body>
</html>
