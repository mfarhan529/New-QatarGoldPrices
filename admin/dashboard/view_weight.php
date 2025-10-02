<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../"); 
    exit();
}
include '../includes/db.php';

// ✅ Success or update alerts
$success_alert = isset($_GET['success']);
$update_alert  = isset($_GET['updated']);

$sql = "SELECT * FROM weight ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Currencies Table</title>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<?php if ($success_alert) { ?>
    <div class="alert-success" id="successAlert">✅ Currency added successfully!</div>
<?php } ?>
<?php if ($update_alert) { ?>
    <div class="alert-success" id="successAlert">✅ Data updated successfully!</div>
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

.tables-container { margin-left: 350px; margin-right: 20px; padding: 20px; width: 900px; }
.tables-container table { border-collapse: collapse; width: 100%; background: #fff; border-radius: 8px; margin-bottom: 30px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.tables-container th { background: #1e3a8a; color: #fff; padding: 10px; text-align: center; }
.tables-container td { padding: 10px; text-align: center; border: 1px solid #ddd; }
.add-btn { display: inline-block; margin-bottom: 15px; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 6px; }
.add-btn:hover { background: #218838; }
.edit-btn { color: blue; text-decoration: none; font-size: 18px; }
.edit-btn:hover { color: darkblue; }
</style>

<div class="tables-container">
  <h2 style="text-align:center;">Weights</h2>
  <a class="add-btn" href="add_weight.php">+ Add Weight</a>
  
  <table>
      <tr>
          <th>ID</th>
          <th>Unit</th>
         
      </tr>
      <?php if ($result->num_rows > 0) { 
          while($row = $result->fetch_assoc()) { ?>
      <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['unit']) ?></td>
          
      </tr>
      <?php } } else { ?>
      <tr><td colspan="3">No weight found.</td></tr>
      <?php } ?>
  </table>
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

document.querySelectorAll('.dropdown-toggle').forEach(item => {
  item.addEventListener('click', function(e) {
    e.preventDefault();
    this.parentElement.classList.toggle('open');
  });
});
</script>

</body>
</html>
