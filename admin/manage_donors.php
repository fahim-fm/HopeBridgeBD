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
    mysqli_query($conn, "DELETE FROM users WHERE id='$del_id' AND role='donor'");
    header("Location: manage_donors.php");
    exit;
}

// Fetch all donors
$donors = mysqli_query($conn, "SELECT * FROM users WHERE role='donor' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Donors - Admin</title>
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
  <h3 class="mb-4">Manage Donors</h3>

  <table class="table table-bordered table-striped">
    <thead class="table-success">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Area</th>
        <th>Registered</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($donor = mysqli_fetch_assoc($donors)){ ?>
      <tr>
        <td><?php echo $donor['id']; ?></td>
        <td><?php echo htmlspecialchars($donor['name']); ?></td>
        <td><?php echo htmlspecialchars($donor['email']); ?></td>
        <td><?php echo htmlspecialchars($donor['phone']); ?></td>
        <td><?php echo htmlspecialchars($donor['area']); ?></td>
        <td><?php echo $donor['created_at']; ?></td>
        <td>
          <a href="edit_donor.php?id=<?php echo $donor['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="manage_donors.php?delete=<?php echo $donor['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this donor?')">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p>&copy; 2025 HopeBridgeBD | Admin Panel</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
