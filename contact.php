<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - HopeBridge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
  <h2 class="text-center fw-bold mb-4">Contact Us</h2>
  <p class="text-center text-muted mb-5">We’d love to hear from you! Fill out the form below and we’ll get back to you soon.</p>
  
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php
      if(isset($_POST['send'])){
          $name = mysqli_real_escape_string($conn, $_POST['name']);
          $email = mysqli_real_escape_string($conn, $_POST['email']);
          $phone = mysqli_real_escape_string($conn, $_POST['phone']);
          $subject = mysqli_real_escape_string($conn, $_POST['subject']);
          $message = mysqli_real_escape_string($conn, $_POST['message']);
          
          $query = "INSERT INTO messages (name, email, phone, subject, message) 
                    VALUES ('$name','$email','$phone','$subject','$message')";
          if(mysqli_query($conn, $query)){
              echo '<div class="alert alert-success">Your message has been sent successfully!</div>';
          } else {
              echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
          }
      }
      ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea name="message" rows="5" class="form-control" required></textarea>
        </div>
        <button type="submit" name="send" class="btn btn-success w-100">Send Message</button>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
