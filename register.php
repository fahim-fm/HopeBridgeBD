<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
  header("Location: donor_dashboard.php");
  exit;
}

$success = $error = '';
$formData = ['name' => '', 'email' => '', 'phone' => '', 'area' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $phone = trim($_POST['phone'] ?? '');
  $area = trim($_POST['area'] ?? '');

  $formData = compact('name', 'email', 'phone', 'area');

  // Check duplicate email
  $chk = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
  mysqli_stmt_bind_param($chk, 's', $email);
  mysqli_stmt_execute($chk);
  mysqli_stmt_store_result($chk);

  if (mysqli_stmt_num_rows($chk) > 0) {
    $error = "This email is already registered. <a href='login.php'>Login instead?</a>";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $ins = mysqli_prepare(
      $conn,
      "INSERT INTO users (name,email,password,phone,area,role) VALUES (?,?,?,?,?,'donor')"
    );
    mysqli_stmt_bind_param($ins, 'sssss', $name, $email, $hashed, $phone, $area);
    if (mysqli_stmt_execute($ins)) {
      $success = "Registration successful! <a href='login.php'>Login now →</a>";
      $formData = ['name' => '', 'email' => '', 'phone' => '', 'area' => ''];
    } else {
      $error = "Something went wrong. Please try again.";
    }
    mysqli_stmt_close($ins);
  }
  mysqli_stmt_close($chk);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — HopeBridgeBD</title>
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
              <img src="assets/img/logo.png" alt="HopeBridgeBD" width="80" loading="lazy">
            </a>
            <h3 class="fw-semibold text-success mt-2" style="font-size:1.5rem">Donor Registration</h3>
            <p class="text-muted small">Create your free donor account</p>
          </div>

          <?php if ($error):
            echo "<div class='alert alert-danger py-2'>$error</div>"; endif; ?>
          <?php if ($success):
            echo "<div class='alert alert-success py-2'>$success</div>"; endif; ?>

          <form method="post" novalidate>

            <div class="mb-3">
              <label class="form-label" for="name">Full Name</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required
                value="<?php echo htmlspecialchars($formData['name']); ?>">
            </div>

            <div class="mb-3">
              <label class="form-label" for="email">Email Address</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required
                value="<?php echo htmlspecialchars($formData['email']); ?>">
            </div>

            <div class="mb-3">
              <label class="form-label" for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control"
                placeholder="Create a strong password" required minlength="6">
            </div>

            <div class="mb-3">
              <label class="form-label" for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g., 017xxxxxxxx" required
                value="<?php echo htmlspecialchars($formData['phone']); ?>">
            </div>

            <div class="mb-4">
              <label class="form-label" for="area">Area</label>
              <input type="text" id="area" name="area" class="form-control" placeholder="Feni / Cumilla / Others"
                required value="<?php echo htmlspecialchars($formData['area']); ?>">
            </div>

            <button type="submit" class="btn btn-success w-100 py-2">Create Account</button>
          </form>

          <div class="text-center mt-3" style="font-size:.9rem">
            <p class="mb-1">Already have an account? <a href="login.php">Login</a></p>
            <a href="index.php" class="text-decoration-none text-muted">← Back to Home</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>