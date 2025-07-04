<?php
require 'includes/db.php';

// Add more coins
$coin_ids = 'bitcoin,ethereum,tether,binancecoin,solana,ripple,cardano,dogecoin';
$prices = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=$coin_ids&vs_currencies=usd"), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AmeedoFx - Buy & Sell Crypto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f4f4f4;
    }
    .ticker {
      background: #111;
      color: #0f0;
      font-family: 'Orbitron', monospace;
      font-size: 1.2rem;
      overflow: hidden;
      white-space: nowrap;
    }
    .ticker div {
      display: inline-block;
      padding-left: 100%;
      animation: ticker 20s linear infinite;
    }
    @keyframes ticker {
      0% { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }
    .price-card {
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border-radius: 15px;
      background: white;
      padding: 20px;
      transition: 0.3s ease-in-out;
    }
    .price-card:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/header.php'; ?>

<!-- Hero -->
<section class="bg-dark text-white text-center p-5">
  <h1 class="display-4 fw-bold">AmeedoFx</h1>
  <p class="lead">Buy and Sell Cryptocurrency Instantly, Securely and Fast</p>
  <a href="buy.js" class="btn btn-warning btn-lg m-2">Buy</a>
  <a href="sell.php" class="btn btn-outline-light btn-lg m-2">Sell</a>
</section>

<!-- Price Ticker -->
<div class="ticker py-2">
  <div>
    <?php foreach ($prices as $coin => $data): ?>
      <?= strtoupper($coin) ?>: $<?= number_format($data['usd'], 2) ?> &nbsp;&nbsp;&nbsp;
    <?php endforeach; ?>
  </div>
</div>

<!-- Price Cards -->
<section class="container my-5">
  <h2 class="text-center mb-4">AmeedoFX Live Prices</h2>
  <div class="row g-4">
    <?php foreach ($prices as $coin => $data): ?>
      <div class="col-6 col-md-3">
        <div class="price-card text-center">
          <h5><?= strtoupper($coin) ?></h5>
          <p class="fw-bold text-success">$<?= number_format($data['usd'], 2) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
  const navCollapse = document.querySelector('.navbar-collapse');

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 992) {
        new bootstrap.Collapse(navCollapse).toggle();
      }
    });
  });
</script>

</body>
</html>
