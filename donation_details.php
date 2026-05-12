<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
  header("Location: donations.php");
  exit;
}

$id = (int) $_GET['id'];
$stmt = mysqli_prepare(
  $conn,
  "SELECT d.*, u.name AS donor_name, u.phone, u.email
     FROM donations d
     JOIN users u ON d.donor_id = u.id
     WHERE d.id = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$donation = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$donation) {
  header("Location: donations.php");
  exit;
}

$statusClass = match ($donation['status']) {
  'Claimed' => 'bg-warning text-dark',
  'Delivered' => 'bg-primary text-white',
  default => 'bg-success text-white',
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($donation['title']); ?> — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="donations.php">Donations</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($donation['title']); ?></li>
      </ol>
    </nav>

    <div class="row g-4 g-lg-5 align-items-start">
      <!-- Image -->
      <div class="col-md-5">
        <img
          src="<?php echo !empty($donation['image']) ? 'uploads/' . htmlspecialchars($donation['image']) : 'assets/img/no-image.png'; ?>"
          class="img-fluid rounded shadow w-100" alt="<?php echo htmlspecialchars($donation['title']); ?>"
          style="max-height:420px; object-fit:cover;">
      </div>

      <!-- Details -->
      <div class="col-md-7">
        <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($donation['title']); ?></h2>

        <div class="d-flex flex-wrap gap-2 mb-3">
          <span class="badge bg-secondary"><?php echo htmlspecialchars($donation['category']); ?></span>
          <span class="badge <?php echo $statusClass; ?>"><?php echo $donation['status']; ?></span>
          <span class="badge bg-light text-dark border">
            <i class="fas fa-map-marker-alt text-danger me-1"></i>
            <?php echo htmlspecialchars($donation['area']); ?>
          </span>
        </div>

        <p class="text-muted"><?php echo nl2br(htmlspecialchars($donation['description'])); ?></p>

        <hr>

        <h5 class="fw-bold mb-3">Donor Information</h5>
        <p class="mb-1">
          <i class="fas fa-user text-success me-2"></i>
          <strong><?php echo htmlspecialchars($donation['donor_name']); ?></strong>
        </p>
        <p class="mb-1">
          <i class="fas fa-envelope text-success me-2"></i>
          <a href="mailto:<?php echo htmlspecialchars($donation['email']); ?>">
            <?php echo htmlspecialchars($donation['email']); ?>
          </a>
        </p>
        <p class="mb-3">
          <i class="fas fa-phone text-success me-2"></i>
          <a href="tel:<?php echo htmlspecialchars($donation['phone']); ?>">
            <?php echo htmlspecialchars($donation['phone']); ?>
          </a>
        </p>

        <a href="donations.php" class="btn btn-outline-secondary me-2">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="login.php" class="btn btn-success">Donate Now</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>