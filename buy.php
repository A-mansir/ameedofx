<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get live prices
$coin_ids = 'bitcoin,ethereum,tether,binancecoin,solana,ripple,cardano,dogecoin';
$prices = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=$coin_ids&vs_currencies=usd"), true);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crypto = $_POST["crypto"];
    $amount = floatval($_POST["amount"]);
    $price = $prices[$crypto]["usd"];
    $total = $price * $amount;
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, crypto, amount, price, total) VALUES (?, 'buy', ?, ?, ?, ?)");
    $stmt->execute([$user_id, $crypto, $amount, $price, $total]);

    $success = "Buy order placed successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Buy Crypto - AmeedoFxz</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width:600px">
  <h2>Buy Cryptocurrency</h2>
  <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Select Coin</label>
      <select name="crypto" class="form-select" required>
        <?php foreach ($prices as $coin => $data): ?>
          <option value="<?= $coin ?>"><?= strtoupper($coin) ?> - $<?= number_format($data["usd"], 2) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Amount (e.g. 0.5)</label>
      <input type="number" name="amount" class="form-control" step="0.0001" required>
    </div>
    <button class="btn btn-success w-100">Place Buy Order</button>
  </form>
</div>
</body>
</html>
