<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

// ✅ Fetch all users
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Users</title>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="table-wrapper">
  <h2>👥 All Users</h2>

  <!-- Success Message -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <p class="success-msg">✅ User added successfully!</p>
  <?php endif; ?>

  <table class="user-table">
    <tr>
      <th>Full Name</th>
      <th>Email</th>
      <th>Phone No</th>
      <th>Country</th>
      <th>Actions</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']); ?></td>
          <td><?= htmlspecialchars($row['email']); ?></td>
          <td><?= htmlspecialchars($row['phone_no']); ?></td>
          <td><?= htmlspecialchars($row['country']); ?></td>
          <td>
            <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn-icon edit" title="Edit">
              ✏
            </a>
            <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
              🗑
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="4">No users found.</td>
      </tr>
    <?php endif; ?>
  </table>
</div>

<style>
  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
  }
  .table-wrapper {
    margin-left: 300px;
    padding: 40px 20px;
    width: 700px;
  }
  h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #1e3a8a;
    font-size: 26px;
    font-weight: 700;
  }
  .success-msg {
    text-align: center;
    color: green;
    font-weight: bold;
    margin-bottom: 20px;
  }
  .user-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  }
  .user-table th, .user-table td {
    padding: 12px 14px;
    border: 1px solid #ddd;
    text-align: center;
  }
  .user-table th {
    background: #1e3a8a;
    color: #fff;
    font-size: 15px;
  }
  .btn-icon {
    font-size: 18px;
    margin: 0 5px;
    text-decoration: none;
    display: inline-block;
    transition: transform 0.2s ease;
  }
  .btn-icon.edit { color: #3498db; }
  .btn-icon.delete { color: #e74c3c; }
  .btn-icon:hover { transform: scale(1.2); }
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
