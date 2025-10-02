<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../"); // Redirect to login if not logged in
    exit();
}

include '../includes/db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $symbol = trim($_POST['Symbol']); // ✅ Match with form name
    
    if (empty($symbol)) {
        $error = "❌ Symbol cannot be empty.";
    } else {
        $sql = "INSERT INTO currencies (`Symbol`) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $symbol);

        if ($stmt->execute()) {
            header("Location: view_currency.php?success=1");
            exit();
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Currency</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
    }

    .form-wrapper {
      margin-left: 400px; /* Sidebar offset */
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
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #1e3a8a;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 8px;
      color: #222;
      font-size: 15px;
    }

    input, select {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      transition: all 0.25s ease;
      background: #fafafa;
    }

    input:focus, select:focus {
      border-color: #1e3a8a;
      background: #fff;
      box-shadow: 0 0 8px rgba(30,58,138,0.15);
      outline: none;
    }

    button {
      margin-top: 25px;
      padding: 14px;
      background: linear-gradient(135deg, #1e3a8a, #152c65);
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 17px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: linear-gradient(135deg, #152c65, #0d1a3a);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .error {
      color: red;
      margin-top: 15px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="form-wrapper">
  <div class="form-container">
    <h2>➕ Add Currency</h2>
    <form method="POST" action="">
      
      <div class="form-group">
        <label for="Symbol">Enter Symbol</label>
        <input 
          type="text" 
          name="Symbol" 
          id="Symbol" 
          placeholder="Enter Symbol..." 
          pattern="[A-Za-z0-9]+" 
          required>
      </div>

      <button type="submit">💰 Save Currency</button>
    </form>

    <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
  </div>
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
