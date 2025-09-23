
<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Fetch statistics
$donors_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='donor'"))['total'];
$donations_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations"))['total'];
$volunteers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='volunteer'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - HopeBridge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="manage_donors.php">Donors</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_donations.php">Donations</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_volunteers.php">Volunteers</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_messages.php">Messages</a></li>
         <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
        <li class="nav-item"><a class="nav-link btn btn-warning text-dark ms-2" href="../index.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h2 class="mb-4">Welcome, <?php echo $_SESSION['admin_name']; ?></h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Total Donors</h5>
          <p class="display-6 text-success"><?php echo $donors_count; ?></p>
          <a href="manage_donors.php" class="btn btn-success btn-sm">Manage Donors</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Total Donations</h5>
          <p class="display-6 text-warning"><?php echo $donations_count; ?></p>
          <a href="manage_donations.php" class="btn btn-warning btn-sm">Manage Donations</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Total Volunteers</h5>
          <p class="display-6 text-info"><?php echo $volunteers_count; ?></p>
          <a href="manage_volunteers.php" class="btn btn-info btn-sm text-dark">Manage Volunteers</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p>&copy; 2025 HopeBridge | Admin Panel</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
