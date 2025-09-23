<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: manage_donors.php");
    exit;
}

$id = $_GET['id'];

// Fetch donor details
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$id' AND role='donor'");
$donor = mysqli_fetch_assoc($res);
if(!$donor){
    echo "<h3 class='text-center mt-5'>Donor not found!</h3>";
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $area = $_POST['area'];

    // If password is provided, hash it
    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', area='$area', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', area='$area' WHERE id='$id'";
    }

    if(mysqli_query($conn, $sql)){
        header("Location: manage_donors.php");
        exit;
    } else {
        $error = "Error updating donor: ".mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Donor - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <h3>Edit Donor</h3>
  <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="post">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($donor['name']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($donor['email']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($donor['phone']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Area</label>
      <input type="text" name="area" class="form-control" value="<?php echo htmlspecialchars($donor['area']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Password (leave blank to keep current)</label>
      <input type="password" name="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Update Donor</button>
    <a href="manage_donors.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
