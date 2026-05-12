<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
if (!isset($_GET['id'])) {
  header("Location: manage_donors.php");
  exit;
}

$id = (int) $_GET['id'];
$res = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'donor' LIMIT 1");
mysqli_stmt_bind_param($res, 'i', $id);
mysqli_stmt_execute($res);
$donor = mysqli_fetch_assoc(mysqli_stmt_get_result($res));
mysqli_stmt_close($res);

if (!$donor) {
  header("Location: manage_donors.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $area = trim($_POST['area'] ?? '');

  if (!empty($_POST['password'])) {
    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = mysqli_prepare(
      $conn,
      "UPDATE users SET name=?,email=?,phone=?,area=?,password=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, 'sssssi', $name, $email, $phone, $area, $hashed, $id);
  } else {
    $stmt = mysqli_prepare(
      $conn,
      "UPDATE users SET name=?,email=?,phone=?,area=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $phone, $area, $id);
  }

  if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: manage_donors.php");
    exit;
  } else {
    $error = "Error updating donor.";
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Donor — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-11 col-md-7 col-lg-5">
        <div class="form-card">

          <div class="d-flex align-items-center gap-2 mb-4">
            <a href="manage_donors.php" class="text-muted text-decoration-none"><i class="fas fa-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Edit Donor</h4>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required
                value="<?php echo htmlspecialchars($donor['name']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required
                value="<?php echo htmlspecialchars($donor['email']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" name="phone" class="form-control" required
                value="<?php echo htmlspecialchars($donor['phone']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Area</label>
              <input type="text" name="area" class="form-control" required
                value="<?php echo htmlspecialchars($donor['area']); ?>">
            </div>
            <div class="mb-4">
              <label class="form-label">New Password <small class="text-muted">(leave blank to keep
                  current)</small></label>
              <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success px-4">Update Donor</button>
              <a href="manage_donors.php" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>