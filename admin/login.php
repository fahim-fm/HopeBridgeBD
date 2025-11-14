<?php
session_start();
include '../db.php';

if(isset($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit;
}

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND role='admin'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - HopeBridgeBD</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" sizes="32x32" type="image/png" href="../favicon.png">

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

    <div class="col-md-4">
        <div class="login-card shadow-sm">

            <div class="text-center mb-4">
                <img src="../assets/img/logo.png" alt="Logo"  width="80">
                <h3 class="brand-title text-success mt-2">Admin Login</h3>
                <p class="text-muted">Access the admin dashboard</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter admin email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">Login</button>
            </form>

            <div class="text-center mt-3 footer-links">
                <a href="../index.php" class="text-decoration-none">← Back to Home</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>
