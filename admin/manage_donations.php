<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Handle deletion
if(isset($_GET['delete'])){
    $del_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM donations WHERE id='$del_id'");
    header("Location: manage_donations.php");
    exit;
}

// Handle status update
if(isset($_GET['status']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $status = $_GET['status'];
    mysqli_query($conn, "UPDATE donations SET status='$status' WHERE id='$id'");
    header("Location: manage_donations.php");
    exit;
}

// Fetch all donations with donor info
$donations = mysqli_query($conn, "SELECT d.*, u.name AS donor_name, u.email AS donor_email FROM donations d JOIN users u ON d.donor_id=u.id ORDER BY d.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Donations - Admin</title>
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
  <h3 class="mb-4">Manage Donations</h3>

  <table class="table table-bordered table-striped">
    <thead class="table-warning">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Donor</th>
        <th>Area</th>
        <th>Status</th>
        <th>Created</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($d = mysqli_fetch_assoc($donations)){ ?>
      <tr>
        <td><?php echo $d['id']; ?></td>
        <td><?php echo htmlspecialchars($d['title']); ?></td>
        <td><?php echo $d['category']; ?></td>
        <td><?php echo htmlspecialchars($d['donor_name']); ?> <br> <small><?php echo htmlspecialchars($d['donor_email']); ?></small></td>
        <td><?php echo htmlspecialchars($d['area']); ?></td>
        <td><?php echo $d['status']; ?></td>
        <td><?php echo $d['created_at']; ?></td>
        <td><img src="../uploads/<?php echo $d['image']; ?>" alt="Donation Image" width="100"></td>
        <td>
          <a href="manage_donations.php?id=<?php echo $d['id']; ?>&status=Available" class="btn btn-sm btn-success">Available</a>
          <a href="manage_donations.php?id=<?php echo $d['id']; ?>&status=Claimed" class="btn btn-sm btn-warning text-dark">Claimed</a>
          <a href="manage_donations.php?id=<?php echo $d['id']; ?>&status=Delivered" class="btn btn-sm btn-primary">Delivered</a>
          <a href="manage_donations.php?delete=<?php echo $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this donation?')">Delete</a>
        </td>
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
