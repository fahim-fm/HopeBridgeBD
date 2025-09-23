<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Handle deletion (sanitize id)
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM messages WHERE id={$del_id}");
    header("Location: admin_messages.php");
    exit;
}

// Fetch messages
$result = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Messages - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
  <h3 class="mb-4">Contact Messages <small class="text-muted">(<?php echo mysqli_num_rows($result); ?>)</small></h3>

  <?php if(mysqli_num_rows($result) == 0): ?>
    <div class="alert alert-info">No messages found.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
           
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Received</th>
            <th style="min-width:140px">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['subject']); ?></td>
            <td style="max-width:400px; white-space:pre-wrap;"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
              <a class="btn btn-sm btn-danger mb-1 d-inline-block"
                 href="admin_messages.php?delete=<?php echo $row['id']; ?>"
                 onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
