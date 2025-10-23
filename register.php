<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - HopeBridgeBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="text-center text-success mb-4">Donor Registration</h3>
          <?php
          if($_SERVER['REQUEST_METHOD'] == 'POST'){
              $name = $_POST['name'];
              $email = $_POST['email'];
              $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
              $phone = $_POST['phone'];
              $area = $_POST['area'];

              $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
              if(mysqli_num_rows($check) > 0){
                  echo "<div class='alert alert-danger'>Email already exists</div>";
              } else {
                  $sql = "INSERT INTO users(name,email,password,phone,area,role) 
                          VALUES('$name','$email','$password','$phone','$area','donor')";
                  if(mysqli_query($conn, $sql)){
                      echo "<div class='alert alert-success'>Registered successfully! <a href='login.php'>Login</a></div>";
                  } else {
                      echo "<div class='alert alert-danger'>Error: ".mysqli_error($conn)."</div>";
                  }
              }
          }
          ?>
          <form method="post">
            <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
            <input type="text" name="phone" class="form-control mb-2" placeholder="Phone" required>
            <input type="text" name="area" class="form-control mb-2" placeholder="Area (Feni/Cumilla)" required>
            <button type="submit" class="btn btn-success w-100">Register</button>
          </form>
          <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
