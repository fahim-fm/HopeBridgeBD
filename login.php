<?php
session_start();
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if($user['role'] == 'admin'){
            header("Location: admin/dashboard.php");
        } elseif($user['role'] == 'donor'){
            header("Location: donor_dashboard.php");
        } elseif($user['role'] == 'volunteer'){
            header("Location: volunteer_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid login credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - HopeBridge</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f3f5f7;
      font-family: "Segoe UI", sans-serif;
    }
    .login-card {
      border-radius: 12px;
      padding: 35px;
      background: #fff;
    }
    .brand-title {
      font-size: 26px;
      font-weight: 600;
    }
    .footer-links {
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">

  <div class="col-md-5">
    <div class="login-card shadow-sm">

      <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo"  width="80">
        <h3 class="brand-title text-success mt-2">HopeBridge Login</h3>
        <p class="text-muted">Access your dashboard</p>
      </div>

      <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

      <form method="post">

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2">Login</button>
      </form>

      <div class="text-center mt-3 footer-links">
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <a href="index.php" class="text-decoration-none">← Back to Home</a>
      </div>

    </div>
  </div>

</div>

</body>
</html>
