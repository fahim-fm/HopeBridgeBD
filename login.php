<?php
session_start();
include 'db.php';

// Already logged in → redirect
if (isset($_SESSION['user_id'])) {
  $role = $_SESSION['role'];
  if ($role === 'admin')
    header("Location: admin/dashboard.php");
  elseif ($role === 'donor')
    header("Location: donor_dashboard.php");
  elseif ($role === 'volunteer')
    header("Location: volunteer_dashboard.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? LIMIT 1");
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    if ($user['role'] === 'admin')
      header("Location: admin/dashboard.php");
    elseif ($user['role'] === 'donor')
      header("Location: donor_dashboard.php");
    elseif ($user['role'] === 'volunteer')
      header("Location: volunteer_dashboard.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — HopeBridgeBD</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center min-vh-100 py-4">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-11 col-sm-9 col-md-7 col-lg-5">
        <div class="auth-card">

          <div class="text-center mb-4">
            <a href="index.php">
              <img src="assets/img/logo.png" alt="HopeBridgeBD Logo" width="80" loading="lazy">
            </a>
            <h3 class="fw-semibold text-success mt-2" style="font-size:1.5rem">HopeBridgeBD Login</h3>
            <p class="text-muted small">Access your dashboard</p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger text-center py-2"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label" for="email">Email Address</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="mb-4">
              <label class="form-label" for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control"
                placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">Login</button>
          </form>

          <div class="text-center mt-3" style="font-size:.9rem">
            <p class="mb-1">Don't have an account? <a href="register.php">Register here</a></p>
            <a href="index.php" class="text-decoration-none text-muted">← Back to Home</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>