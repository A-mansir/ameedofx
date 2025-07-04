<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $password]);
        $success = "Account created successfully. <a href='login.php'>Login here</a>.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - AmeedoFxz</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-5" style="max-width:500px">
  <h2 class="mb-3">Create Account</h2>
  <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
  <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="POST">
    <input name="name" class="form-control mb-3" placeholder="Your Name" required />
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required />
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required />
    <button class="btn btn-dark w-100">Register</button>
  </form>
</div>
</body>
</html>
