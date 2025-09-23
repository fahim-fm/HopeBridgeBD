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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center text-success mb-4">Login</h3>
          <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
          <form method="post">
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
            <button type="submit" class="btn btn-success w-100">Login</button>
          </form>
          <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
          <a href="index.php">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
