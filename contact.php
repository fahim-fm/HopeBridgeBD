<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - HopeBridgeBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

  <style>
    body {
      background: #f3f5f7;
      font-family: "Segoe UI", sans-serif;
    }
    .contact-card {
      background: #fff;
      padding: 35px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">

  <div class="text-center mb-5">
    <h2 class="fw-bold">Contact Us</h2>
    <p class="text-muted">We’d love to hear from you! Fill out the form below and we’ll respond soon.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-7">

      <div class="contact-card">

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
                echo '<div class="alert alert-success text-center">Your message has been sent successfully!</div>';
            } else {
                echo '<div class="alert alert-danger text-center">Something went wrong. Please try again.</div>';
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
            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="phone" class="form-control" placeholder="e.g., 017xxxxxxxx" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" placeholder="Enter message subject" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Write your message here..." required></textarea>
          </div>

          <button type="submit" name="send" class="btn btn-success w-100 py-2">
            Send Message
          </button>

        </form>

      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
