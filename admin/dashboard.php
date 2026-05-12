<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$adminName = htmlspecialchars($_SESSION['admin_name']);
$donors_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM users WHERE role='donor'"))['t'];
$donations_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM donations"))['t'];
$volunteers_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM users WHERE role='volunteer'"))['t'];
$messages_count = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM messages"))['t'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <h3 class="fw-bold mb-1">Welcome, <?php echo $adminName; ?>!</h3>
    <p class="text-muted mb-4">Here's an overview of the platform.</p>

    <div class="row g-4">

      <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body py-4">
            <i class="fas fa-users fa-2x text-success mb-2"></i>
            <h2 class="fw-bold text-success"><?php echo $donors_count; ?></h2>
            <p class="text-muted mb-3">Total Donors</p>
            <a href="manage_donors.php" class="btn btn-success btn-sm px-3">Manage</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body py-4">
            <i class="fas fa-box-open fa-2x text-warning mb-2"></i>
            <h2 class="fw-bold text-warning"><?php echo $donations_count; ?></h2>
            <p class="text-muted mb-3">Total Donations</p>
            <a href="manage_donations.php" class="btn btn-warning btn-sm px-3">Manage</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body py-4">
            <i class="fas fa-hands-helping fa-2x text-info mb-2"></i>
            <h2 class="fw-bold text-info"><?php echo $volunteers_count; ?></h2>
            <p class="text-muted mb-3">Volunteers</p>
            <a href="manage_volunteers.php" class="btn btn-info btn-sm px-3 text-dark">Manage</a>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body py-4">
            <i class="fas fa-envelope fa-2x text-danger mb-2"></i>
            <h2 class="fw-bold text-danger"><?php echo $messages_count; ?></h2>
            <p class="text-muted mb-3">Messages</p>
            <a href="admin_messages.php" class="btn btn-danger btn-sm px-3">View</a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HopeBridgeBD | Admin Panel</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>