<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
if (!isset($_GET['id'])) {
  header("Location: manage_volunteers.php");
  exit;
}

$id = (int) $_GET['id'];
$res = mysqli_prepare(
  $conn,
  "SELECT v.*, u.name, u.email, u.phone, u.area
     FROM volunteers v JOIN users u ON v.user_id = u.id
     WHERE v.id = ? LIMIT 1"
);
mysqli_stmt_bind_param($res, 'i', $id);
mysqli_stmt_execute($res);
$volunteer = mysqli_fetch_assoc(mysqli_stmt_get_result($res));
mysqli_stmt_close($res);

if (!$volunteer) {
  header("Location: manage_volunteers.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $area = trim($_POST['area'] ?? '');
  $availability = in_array($_POST['availability'] ?? '', ['Active', 'Not Available'])
    ? $_POST['availability'] : 'Active';

  // Update users table
  $s1 = mysqli_prepare($conn, "UPDATE users SET name=?,phone=?,area=? WHERE id=?");
  mysqli_stmt_bind_param($s1, 'sssi', $name, $phone, $area, $volunteer['user_id']);
  mysqli_stmt_execute($s1);
  mysqli_stmt_close($s1);

  // Image upload
  $image = $volunteer['image'];
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

  // Update volunteers table
  $s2 = mysqli_prepare($conn, "UPDATE volunteers SET availability=?,image=? WHERE id=?");
  mysqli_stmt_bind_param($s2, 'ssi', $availability, $image, $id);
  mysqli_stmt_execute($s2);
  mysqli_stmt_close($s2);

  header("Location: manage_volunteers.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Volunteer — Admin</title>
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
            <a href="manage_volunteers.php" class="text-muted text-decoration-none"><i
                class="fas fa-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Edit Volunteer</h4>
          </div>

          <form method="post" enctype="multipart/form-data" novalidate>

            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required
                value="<?php echo htmlspecialchars($volunteer['name']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" name="phone" class="form-control" required
                value="<?php echo htmlspecialchars($volunteer['phone']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Area</label>
              <input type="text" name="area" class="form-control" required
                value="<?php echo htmlspecialchars($volunteer['area']); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Availability</label>
              <select name="availability" class="form-select">
                <option value="Active" <?php if ($volunteer['availability'] === 'Active')
                  echo 'selected'; ?>>Active
                </option>
                <option value="Not Available" <?php if ($volunteer['availability'] === 'Not Available')
                  echo 'selected'; ?>>Not Available</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">Profile Photo (optional)</label>
              <input type="file" name="image" class="form-control" accept="image/*">
              <?php if ($volunteer['image']): ?>
                <div class="mt-2">
                  <img src="../uploads/<?php echo htmlspecialchars($volunteer['image']); ?>" width="70"
                    class="rounded-circle border" alt="Current Photo">
                  <small class="text-muted ms-2">Current photo</small>
                </div>
              <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success px-4">Update Volunteer</button>
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