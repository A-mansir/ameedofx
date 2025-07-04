<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
  .navbar-custom {
    background: linear-gradient(90deg, #000000, #1a1a1a);
    box-shadow: 0 3px 10px rgba(204, 46, 46, 0.4);
  }
  .nav-link {
    color: #ccc !important;
    margin-left: 10px;
  }
  .nav-link:hover, .nav-link.active {
    color: #fff !important;
    border-bottom: 2px solid gold;
  }
  .navbar-brand {
    font-weight: bold;
    font-size: 1.5rem;
    color: gold !important;
  }
  .btn-nav {
    font-size: 0.9rem;
    margin-left: 10px;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-currency-bitcoin"></i> AmeedoFx</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'buy.php' ? 'active' : '' ?>" href="buy.php">Buy</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sell.php' ? 'active' : '' ?>" href="sell.php">Sell</a></li>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : '' ?>" href="transactions.php">My Trades</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-danger btn-nav" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-sm btn-outline-light btn-nav" href="login.php">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-warning btn-nav" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
