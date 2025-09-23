<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Fetch stats
$total_donations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations"))['total'];
$total_donors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='donor'"))['total'];
$total_volunteers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM volunteers"))['total'];

// Fetch recent donations
$recent_donations = mysqli_query($conn, "
    SELECT d.*, u.name AS donor_name
    FROM donations d
    JOIN users u ON d.donor_id = u.id
    ORDER BY d.created_at DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Reports</title>
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
  <h3 class="mb-4">Platform Reports</h3>

  <!-- Stats Cards -->
  <div class="row mb-5">
    <div class="col-md-4">
      <div class="card text-white bg-success mb-3">
        <div class="card-body text-center">
          <h2><?php echo $total_donations; ?></h2>
          <p>Total Donations</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-primary mb-3">
        <div class="card-body text-center">
          <h2><?php echo $total_donors; ?></h2>
          <p>Total Donors</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-warning mb-3">
        <div class="card-body text-center">
          <h2><?php echo $total_volunteers; ?></h2>
          <p>Total Volunteers</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Donations Table -->
  <h4>Recent Donations</h4>
  <table class="table table-bordered table-striped">
    <thead class="table-info">
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Donor</th>
        <th>Area</th>
        <th>Status</th>
        <th>Created At</th>
      </tr>
    </thead>
    <tbody>
      <?php while($don = mysqli_fetch_assoc($recent_donations)){ ?>
      <tr>
        <td><?php echo htmlspecialchars($don['title']); ?></td>
        <td><?php echo $don['category']; ?></td>
        <td><?php echo htmlspecialchars($don['donor_name']); ?></td>
        <td><?php echo $don['area']; ?></td>
        <td><?php echo $don['status']; ?></td>
        <td><?php echo $don['created_at']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p>&copy; 2025 HopeBridge | Admin Panel</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
