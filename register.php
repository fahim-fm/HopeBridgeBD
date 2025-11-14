<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - HopeBridgeBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

  <style>
    body {
      background: #f3f5f7;
      font-family: "Segoe UI", sans-serif;
    }
    .register-card {
      border-radius: 12px;
      padding: 35px;
      background: #fff;
    }
    .brand-title {
      font-size: 26px;
      font-weight: 600;
    }
    .footer-links {
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
  
  <div class="col-md-5">
    <div class="register-card shadow-sm">

      <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo"  width="80">
        <h3 class="brand-title text-success mt-2">Donor Registration</h3>
        <p class="text-muted">Create your donor account</p>
      </div>

      <?php
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
          $name = $_POST['name'];
          $email = $_POST['email'];
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
          $phone = $_POST['phone'];
          $area = $_POST['area'];

          $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
          if(mysqli_num_rows($check) > 0){
              echo "<div class='alert alert-danger text-center'>Email already exists</div>";
          } else {
              $sql = "INSERT INTO users(name,email,password,phone,area,role) 
                      VALUES('$name','$email','$password','$phone','$area','donor')";
              if(mysqli_query($conn, $sql)){
                  echo "<div class='alert alert-success text-center'>Registered successfully! <a href='login.php'>Login</a></div>";
              } else {
                  echo "<div class='alert alert-danger text-center'>Error: ".mysqli_error($conn)."</div>";
              }
          }
      }
      ?>

      <form method="post">

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" placeholder="e.g., 017xxxxxxxx" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Area</label>
          <input type="text" name="area" class="form-control" placeholder="Feni / Cumilla / Others" required>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2">Register</button>
      </form>

      <div class="text-center mt-3 footer-links">
        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>

    </div>
  </div>

</div>

</body>
</html>
