<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../");
    exit();
}

include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM cities WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_city.php?deleted=1");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error deleting user: " . $stmt->error . "</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>❌ Invalid request.</p>";
}
