<?php
session_start();
include 'db.php';

// If already logged in as donor, redirect to full form
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'donor') {
  header("Location: donation_form.php");
  exit;
}

$categories = ['Cooked Food', 'Dry Food', 'Clothes', 'Winter Items', 'Stationery/Books'];
$statuses = ['Available', 'Claimed', 'Delivered'];

$title = $description = $category = $status = $area = '';
$edit = false;
$edit_id = 0;

// Edit mode (admin/donor may link here with ?id=X)
if (isset($_GET['id'])) {
  $edit_id = (int) $_GET['id'];
  $stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM donations WHERE id = ? LIMIT 1"
  );
  mysqli_stmt_bind_param($stmt, 'i', $edit_id);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);

  if ($row) {
    $edit = true;
    $title = $row['title'];
    $description = $row['description'];
    $category = $row['category'];
    $status = $row['status'];
    $area = $row['area'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $edit ? 'Edit' : 'Donate Now'; ?> — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-11 col-md-8 col-lg-7">

        <div class="card shadow border-0 rounded-3">
          <div class="card-header bg-success text-white text-center fw-bold py-3 fs-5">
            <?php echo $edit ? 'Edit Donation' : 'Add Donation'; ?>
          </div>
          <div class="card-body p-4">
            <form method="post" action="donation_save.php" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $edit ? $edit_id : ''; ?>">

              <div class="mb-3">
                <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="form-control"
                  value="<?php echo htmlspecialchars($title); ?>" placeholder="e.g., Winter clothes for children"
                  required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold" for="category">Category <span class="text-danger">*</span></label>
                <select id="category" name="category" class="form-select" required>
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php if ($category === $cat)
                         echo 'selected'; ?>>
                      <?php echo $cat; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold" for="description">Description <span
                    class="text-danger">*</span></label>
                <textarea id="description" name="description" class="form-control" rows="4"
                  placeholder="Describe your donation items"
                  required><?php echo htmlspecialchars($description); ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold" for="area">Area <span class="text-danger">*</span></label>
                <input type="text" id="area" name="area" class="form-control"
                  value="<?php echo htmlspecialchars($area); ?>" placeholder="e.g., Feni / Cumilla" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold" for="image">
                  Image <?php echo $edit ? '' : '<span class="text-danger">*</span>'; ?>
                </label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" <?php if (!$edit)
                  echo 'required'; ?>>
                <div class="form-text">Accepted: JPG, PNG, WEBP (max 5 MB)</div>
              </div>

              <?php if ($edit): ?>
                <div class="mb-3">
                  <label class="form-label fw-semibold" for="status">Status</label>
                  <select id="status" name="status" class="form-select">
                    <?php foreach ($statuses as $s): ?>
                      <option value="<?php echo $s; ?>" <?php if ($status === $s)
                           echo 'selected'; ?>>
                        <?php echo $s; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endif; ?>

              <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" class="btn btn-success px-4">
                  <?php echo $edit ? 'Update Donation' : 'Add Donation'; ?>
                </button>
                <a href="index.php" class="btn btn-outline-secondary px-4">Cancel</a>
              </div>
            </form>
          </div>

          <?php if (!$edit): ?>
            <div class="card-footer text-center bg-white py-3">
              <small class="text-muted">
                Want to track your donations?
                <a href="login.php" class="text-decoration-none fw-semibold text-success">Login or Register</a>
              </small>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>