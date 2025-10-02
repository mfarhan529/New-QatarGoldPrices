<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

// Insert user when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $country  = trim($_POST['country'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if (!empty($name) && !empty($country) && !empty($phone_no) && !empty($email)) {
        $sql = "INSERT INTO users (name, country, email, phone_no, created_at, updated_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $country, $email, $phone_no);

        if ($stmt->execute()) {
            header("Location: view_user.php?success=1");
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
  <title>Add User</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css"/>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="form-wrapper">
  <div class="form-container">
    <h3 style="text-align:center; margin-bottom:20px;">
      ⭐ Register to get latest updates and offers
    </h3>

    <form method="POST" action="">
      <!-- Name -->
      <div class="form-group">
        <label>Name <span style="color:red">*</span></label>
        <input type="text" name="name" placeholder="Your Name..." required>
      </div>

      <!-- Country -->
      <div class="form-group">
        <label>Country <span style="color:red">*</span></label>
        <select name="country" required>
          <option value="" disabled selected>-- Select Country --</option>
          <option value="Pakistan">Pakistan</option>
          <option value="India">India</option>
          <option value="Qatar">Qatar</option>
          <option value="Oman">Oman</option>
          <option value="United Arab Emirates">United Arab Emirates</option>
          <option value="United States">United States</option>
        </select>
      </div>

      <!-- Phone -->
      <div class="form-group">
        <label>Mobile Number <span style="color:red">*</span></label>
        <input id="phone" type="tel" name="phone_no" placeholder="Enter Phone Number..." 
               pattern="[0-9]+"  required>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label>Email Address <span style="color:red">*</span></label>
        <input type="email" name="email" placeholder="Enter email..." required>
      </div>

      <button type="submit">Submit</button>
    </form>
  </div>
</div>



<style>
  body { font-family:'Segoe UI', Arial, sans-serif; background:#f0f2f5; margin:0; padding:0; }
  .form-wrapper { margin-left:400px; padding:40px 20px; width:800px; display:flex; justify-content:center; }
  .form-container { background:#fff; padding:35px 40px; border-radius:14px; box-shadow:0 6px 20px rgba(0,0,0,0.08); }
  h3 { font-size:24px; font-weight:600; }
  .form-group { margin-bottom:20px; }
  label { font-weight:600; display:block; margin-bottom:8px; color:#222; font-size:15px; }
  input, select {
    width:100%; padding:12px 14px; border-radius:8px; border:1px solid #ccc;
    font-size:15px; transition:all 0.25s ease; background:#fafafa;
  }
  input:focus, select:focus {
    border-color:#1e3a8a; background:#fff; box-shadow:0 0 8px rgba(30,58,138,0.15); outline:none;
  }
  button {
    margin-top:25px; padding:14px;
    background:linear-gradient(135deg,#1e3a8a,#152c65);
    color:#fff; border:none; border-radius:8px; cursor:pointer; width:100%; font-size:17px; font-weight:600;
    transition:all 0.3s ease;
  }
  button:hover {
    background:linear-gradient(135deg,#152c65,#0d1a3a);
    transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.15);
  }
</style>
</body>
</html>
  