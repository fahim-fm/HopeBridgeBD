<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$result = mysqli_query($conn, "SELECT * FROM donations WHERE donor_id='$user_id' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?php echo $name; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

</head>
<body>
<nav class="navbar navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="#">My Dashboard</a>
    <a href="index.php" class="navbar-brand">Home</a>

    
    <a href="logout.php" class="btn btn-warning">Logout</a>
  </div>
</nav>

<div class="container py-4">
  <h3>Welcome, <?php echo $name; ?></h3>
  <a href="donation_form.php" class="btn btn-success my-3">+ Add New Donation</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Created</th>
        <th>image</th>

        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)){ ?>
      <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <td><img src="uploads/<?php echo $row['image']; ?>" width="100"></td>

        <td>
          <a href="donation_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="delete_donation.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this donation?')">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>
