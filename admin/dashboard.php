<?php
require '../includes/db.php';
session_start();

// Simple protection (You can replace this with a proper admin login later)
$admin_allowed = true; // Set to false if you want to restrict

if (!$admin_allowed) {
    die("Unauthorized access");
}

$stmt = $conn->query("SELECT transactions.*, users.name FROM transactions JOIN users ON transactions.user_id = users.id ORDER BY transactions.created_at DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - AmeedoFxz</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">Admin Dashboard</h2>
  <p>All transactions from users:</p>
  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>User</th>
          <th>Type</th>
          <th>Coin</th>
          <th>Amount</th>
          <th>Price</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($transactions as $tx): ?>
        <tr>
          <td><?= htmlspecialchars($tx['name']) ?></td>
          <td><?= strtoupper($tx['type']) ?></td>
          <td><?= strtoupper($tx['crypto']) ?></td>
          <td><?= $tx['amount'] ?></td>
          <td>$<?= number_format($tx['price'], 2) ?></td>
          <td>$<?= number_format($tx['total'], 2) ?></td>
          <td>
            <?php
            if ($tx["status"] == "pending") echo "<span class='badge bg-warning'>Pending</span>";
            elseif ($tx["status"] == "completed") echo "<span class='badge bg-success'>Completed</span>";
            else echo "<span class='badge bg-danger'>Cancelled</span>";
            ?>
          </td>
          <td><?= date("Y-m-d H:i", strtotime($tx["created_at"])) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
