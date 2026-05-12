<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $area = trim($_POST['area'] ?? '');
  $password = $_POST['password'] ?? '';
  $availability = in_array($_POST['availability'] ?? '', ['Active', 'Not Available'])
    ? $_POST['availability'] : 'Active';

  // Check duplicate
  $chk = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
  mysqli_stmt_bind_param($chk, 's', $email);
  mysqli_stmt_execute($chk);
  mysqli_stmt_store_result($chk);

  if (mysqli_stmt_num_rows($chk) > 0) {
    $error = "Email already exists!";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $ins = mysqli_prepare(
      $conn,
      "INSERT INTO users (name,email,password,phone,role,area) VALUES (?,?,?,?,'volunteer',?)"
    );
    mysqli_stmt_bind_param($ins, 'sssss', $name, $email, $hashed, $phone, $area);
    mysqli_stmt_execute($ins);
    $user_id = mysqli_insert_id($conn);
    mysqli_stmt_close($ins);

    // Image upload
    $image = '';
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!empty($_FILES['image']['name'])) {
      $tmp = $_FILES['image']['tmp_name'];
      $mime = mime_content_type($tmp);
      if (in_array($mime, $allowedMime) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir))
          mkdir($targetDir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        move_uploaded_file($tmp, $targetDir . $fname);
        $image = $fname;
      }
    }

    $ins2 = mysqli_prepare(
      $conn,
      "INSERT INTO volunteers (user_id,availability,image) VALUES (?,?,?)"
    );
    mysqli_stmt_bind_param($ins2, 'iss', $user_id, $availability, $image);
    mysqli_stmt_execute($ins2);
    mysqli_stmt_close($ins2);

    header("Location: manage_volunteers.php");
    exit;
  }
  mysqli_stmt_close($chk);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Volunteer — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-11 col-md-8 col-lg-6">
        <div class="form-card">

          <div class="d-flex align-items-center gap-2 mb-4">
            <a href="manage_volunteers.php" class="text-muted text-decoration-none">
              <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0">Add New Volunteer</h4>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data" novalidate>

            <div class="mb-3">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="row g-3">
              <div class="col-sm-6 mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" name="phone" class="form-control" required>
              </div>
              <div class="col-sm-6 mb-3">
                <label class="form-label">Area <span class="text-danger">*</span></label>
                <input type="text" name="area" class="form-control" placeholder="Feni / Cumilla" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
              <label class="form-label">Availability</label>
              <select name="availability" class="form-select">
                <option value="Active">Active</option>
                <option value="Not Available">Not Available</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Profile Photo (optional)</label>
              <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success px-4">Add Volunteer</button>
              <a href="manage_volunteers.php" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>