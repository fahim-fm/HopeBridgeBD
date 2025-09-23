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
    mysqli_query($conn, "DELETE FROM volunteers WHERE id='$del_id'");
    header("Location: manage_volunteers.php");
    exit;
}

// Fetch all volunteers with user info
$volunteers = mysqli_query($conn, "
    SELECT v.*, u.name AS volunteer_name, u.email AS volunteer_email, u.phone AS volunteer_phone, u.area AS volunteer_area
    FROM volunteers v
    JOIN users u ON v.user_id = u.id
    ORDER BY v.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Volunteers - Admin</title>
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
  <h3 class="mb-4">Manage Volunteers</h3>
  <a href="add_volunteer.php" class="btn btn-success mb-3">+ Add New Volunteer</a>

  <table class="table table-bordered table-striped">
    <thead class="table-info">
      <tr>
       
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Area</th>
        <th>Availability</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($v = mysqli_fetch_assoc($volunteers)){ ?>
      <tr>
       
        <td><?php echo htmlspecialchars($v['volunteer_name']); ?></td>
        <td><?php echo htmlspecialchars($v['volunteer_email']); ?></td>
        <td><?php echo htmlspecialchars($v['volunteer_phone']); ?></td>
        <td><?php echo htmlspecialchars($v['volunteer_area']); ?></td>
        <td><?php echo $v['availability']; ?></td>
        <td>
          <?php if($v['image']){ ?>
            <img src="../uploads/<?php echo $v['image']; ?>" width="50" alt="Volunteer">
          <?php } ?>
        </td>
        <td>
          <a href="edit_volunteer.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="manage_volunteers.php?delete=<?php echo $v['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this volunteer?')">Delete</a>
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
