<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

// ✅ Get user by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        die("<p style='color:red; text-align:center;'>❌ User not found.</p>");
    }
} else {
    die("<p style='color:red; text-align:center;'>❌ Invalid request.</p>");
}

// ✅ Update user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');

    if (!empty($name) && !empty($email) && !empty($phone_no)) {
        $update_sql = "UPDATE users SET name = ?, email = ?, phone_no = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $name, $email, $phone_no, $id);

        if ($stmt->execute()) {
            header("Location: view_user.php?updated=1");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ All fields are required.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div class="form-wrapper">
  <div class="form-container">
    <h2>✏ Edit User</h2>
    <form method="POST" action="">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no']); ?>" required>
      </div>
      <button type="submit">💾 Update User</button>
    </form>
  </div>
</div>

<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
  .form-wrapper { margin-left: 400px; padding: 40px 20px; width: 800px; }
  .form-container {
    background: #fff; padding: 35px 40px; border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  }
  h2 { text-align: center; margin-bottom: 30px; color: #1e3a8a; font-size: 26px; font-weight: 700; }
  .form-group { margin-bottom: 20px; }
  label { font-weight: 600; display: block; margin-bottom: 8px; }
  input { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #ccc; }
  input:focus { border-color: #1e3a8a; background: #fff; outline: none; }
  button {
    margin-top: 25px; padding: 14px;
    background: linear-gradient(135deg, #1e3a8a, #152c65);
    color: #fff; border: none; border-radius: 8px; cursor: pointer; width: 100%;
  }
  button:hover { background: linear-gradient(135deg, #152c65, #0d1a3a); }
</style>
</body>
</html>
