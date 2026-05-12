<?php
session_start();
include '../db.php';

if (isset($_SESSION['admin_id'])) {
  header("Location: dashboard.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($admin && password_verify($password, $admin['password'])) {
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid admin credentials.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — HopeBridgeBD</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center min-vh-100 py-4">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-11 col-sm-8 col-md-5 col-lg-4">
        <div class="auth-card">

          <div class="text-center mb-4">
            <a href="../index.php">
              <img src="../assets/img/logo.png" alt="HopeBridgeBD" width="70" loading="lazy">
            </a>
            <h3 class="fw-semibold text-success mt-2" style="font-size:1.4rem">Admin Login</h3>
            <p class="text-muted small">Access the admin dashboard</p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger py-2 text-center"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label" for="email">Admin Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter admin email" required>
            </div>
            <div class="mb-4">
              <label class="form-label" for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                required>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">Login</button>
          </form>

          <div class="text-center mt-3" style="font-size:.85rem">
            <a href="../index.php" class="text-decoration-none text-muted">← Back to Site</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>