<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../"); 
    exit();
}
include '../includes/db.php';

// ✅ Success alert
$success_alert = isset($_GET['success']);

$sql = "SELECT * FROM cities ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cities List</title>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<?php if ($success_alert) { ?>
    <div class="alert-success" id="successAlert">✅ City added successfully!</div>
<?php } ?>

<style>
.alert-success {
    position: fixed;
    top: 0; left: 0; width: 100%;
    background-color: #28a745;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
    font-weight: 600;
    z-index: 9999;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    animation: slideDown 0.5s ease-in-out;
}
@keyframes slideDown { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes fadeOut { from { opacity: 1; } to { opacity: 0; transform: translateY(-20px); } }

.cities-container { 
    margin-left: 350px; 
    margin-right: 20px; 
    padding: 20px; 
    width: 600px;
}
.city-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* two equal columns */
    gap: 15px 20px; /* space between items */
}

</style>

<div class="cities-container">
  <h2 style="text-align:center;">Cities</h2>
  <a class="add-btn" href="add_city.php">+ Add City</a>

  <div class="city-grid">
    <?php if ($result->num_rows > 0) { 
        while($row = $result->fetch_assoc()) { ?>
          <div class="city-item">
            <?= htmlspecialchars($row['city_name']) ?>
          </div>
    <?php } } else { ?>
        <p>No cities found.</p>
    <?php } ?>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const alerts = document.querySelectorAll("#successAlert");
    alerts.forEach(alertBox => {
        setTimeout(() => {
            alertBox.style.animation = "fadeOut 0.8s forwards";
            setTimeout(() => alertBox.remove(), 800);
        }, 3000);
    });
});
</script>

</body>
</html>
