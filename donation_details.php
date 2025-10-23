<?php
include 'db.php';

if(!isset($_GET['id'])){
    header("Location: donations.php");
    exit;
}

$id = $_GET['id'];

// Fetch donation details
$query = "SELECT d.*, u.name AS donor_name, u.phone, u.email 
          FROM donations d 
          JOIN users u ON d.donor_id = u.id 
          WHERE d.id='$id'";
$result = mysqli_query($conn, $query);
$donation = mysqli_fetch_assoc($result);

if(!$donation){
    echo "<h2 class='text-center mt-5'>Donation not found!</h2>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($donation['title']); ?> - HopeBridge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">HopeBridge</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="donations.php">Donations</a></li>
        <li class="nav-item"><a class="nav-link" href="volunteers.php">Volunteers</a></li>
        <li class="nav-item"><a class="nav-link btn btn-warning text-dark ms-2" href="login.php">Donate Now</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-6">
      <img src="<?php echo $donation['image'] ? 'uploads/'.$donation['image'] : 'assets/img/no-image.png'; ?>" 
           class="img-fluid rounded shadow" alt="Donation Image">
    </div>
    <div class="col-md-6">
      <h2 class="fw-bold"><?php echo htmlspecialchars($donation['title']); ?></h2>
      <p><strong>Category:</strong> <?php echo $donation['category']; ?></p>
      <p><strong>Status:</strong> <?php echo $donation['status']; ?></p>
      <p><strong>Area:</strong> <?php echo htmlspecialchars($donation['area']); ?></p>
      <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($donation['description'])); ?></p>
      <hr>
      <h5>Donor Information</h5>
      <p>
        <strong>Name:</strong> <?php echo htmlspecialchars($donation['donor_name']); ?><br>
        <strong>Email:</strong> <a href="mailto:<?php echo $donation['email']; ?>"><?php echo $donation['email']; ?></a><br>
        <strong>Phone:</strong> <a href="tel:<?php echo $donation['phone']; ?>"><?php echo $donation['phone']; ?></a>
      </p>
      <a href="donations.php" class="btn btn-secondary mt-3">Back to Donations</a>
    </div>
  </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p>&copy; 2025 HopeBridge | Serving Feni & Cumilla</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
