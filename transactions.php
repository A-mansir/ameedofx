<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Transactions - AmeedoFxz</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-5">
  <h2>My Transactions</h2>
  <?php if (count($transactions) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Coin</th>
            <th>Amount</th>
            <th>Price ($)</th>
            <th>Total ($)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $tx): ?>
          <tr>
            <td><?= date("Y-m-d H:i", strtotime($tx["created_at"])) ?></td>
            <td><?= strtoupper($tx["type"]) ?></td>
            <td><?= strtoupper($tx["crypto"]) ?></td>
            <td><?= $tx["amount"] ?></td>
            <td>$<?= number_format($tx["price"], 2) ?></td>
            <td>$<?= number_format($tx["total"], 2) ?></td>
            <td>
              <?php
              if ($tx["status"] == "pending") echo "<span class='badge bg-warning'>Pending</span>";
              elseif ($tx["status"] == "completed") echo "<span class='badge bg-success'>Completed</span>";
              else echo "<span class='badge bg-danger'>Cancelled</span>";
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted mt-4">You have no transactions yet.</p>
  <?php endif; ?>
</div>
</body>
</html>
