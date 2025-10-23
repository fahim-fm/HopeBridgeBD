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

</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center text-success mb-4">Admin Login</h3>
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="post">
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                    <p class="mt-3 text-center"><a href="../index.php">Back to Home</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
