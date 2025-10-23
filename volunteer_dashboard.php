<?php
session_start();
include 'db.php';

// Check if volunteer is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'volunteer'){
    header("Location: login.php");
    exit;
}

$volunteer_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Fetch volunteer info with image from volunteers table
$volunteer = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT u.*, v.image, v.availability
    FROM users u
    LEFT JOIN volunteers v ON u.id = v.user_id
    WHERE u.id='$volunteer_id'
"));

// Fetch donations in volunteer's area
$donations = mysqli_query($conn, "
    SELECT * FROM donations 
    WHERE area='{$volunteer['area']}' AND status='Available' 
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteer Dashboard - <?php echo $name; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

  <style>
    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 10px;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="#">Volunteer Dashboard</a>
    <a href="logout.php" class="btn btn-warning">Logout</a>
  </div>
</nav>

<div class="container py-4">

  <h3>Hi , <?php echo $name; ?></h3>
  
  <!-- Volunteer Info -->
  <div class="card mb-4">
    <div class="card-body text-center">
      <?php if(!empty($volunteer['image'])): ?>
        <img src="uploads/<?php echo $volunteer['image']; ?>" alt="Profile Image" class="profile-img">
      <?php else: ?>
        <img src="assets/img/default-profile.png" alt="Profile Image" class="profile-img">
      <?php endif; ?>
      
      <h5 class="card-title"><?php echo $volunteer['name']; ?></h5>
      <p><strong>Email:</strong> <?php echo $volunteer['email']; ?></p>
      <p><strong>Phone:</strong> <?php echo $volunteer['phone']; ?></p>
      <p><strong>Area:</strong> <?php echo $volunteer['area']; ?></p>
      <p><strong>Availability:</strong> <?php echo ucfirst($volunteer['availability'] ?? 'Active'); ?></p>
    </div>
  </div>

  <!-- Donations in Your Area -->
  <h5>Donations in Your Area</h5>
  <table class="table table-bordered table-striped mt-2">
    <thead>
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Donor Name</th>
        <th>Area</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($donations)){
        $donor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='{$row['donor_id']}'"));
      ?>
      <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $donor['name']; ?></td>
        <td><?php echo $row['area']; ?></td>
        <td>
          <a href="donation_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">View</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

</div>
</body>
</html>
