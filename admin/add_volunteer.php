<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $area = $_POST['area'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $availability = $_POST['availability'];

    // Check if email exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $error = "Email already exists!";
    } else {
        // Insert into users table
        mysqli_query($conn, "INSERT INTO users(name,email,password,phone,role,area) 
            VALUES('$name','$email','$password','$phone','volunteer','$area')");
        $user_id = mysqli_insert_id($conn);

        // Handle image upload
        $image = "";
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
            $targetDir = "../uploads/";
            if(!is_dir($targetDir)) mkdir($targetDir);
            $fileName = time()."_".basename($_FILES['image']['name']);
            $targetFile = $targetDir.$fileName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            $image = $fileName;
        }

        // Insert into volunteers table
        mysqli_query($conn, "INSERT INTO volunteers(user_id, availability, image) 
            VALUES('$user_id','$availability','$image')");

        header("Location: manage_volunteers.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Volunteer - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="icon" sizes="32x32" type="image/png" href="../favicon.png">

</head>
<body>

<div class="container py-5">
  <h3>Add New Volunteer</h3>
  <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Area</label>
      <input type="text" name="area" class="form-control" placeholder="Feni/Cumilla" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Availability</label>
      <select name="availability" class="form-control" required>
        <option value="Active">Active</option>
        <option value="Not Available">Not Available</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Image (optional)</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Add Volunteer</button>
    <a href="manage_volunteers.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
