<?php
session_start();
include 'db.php';

$success = $error = '';
$formData = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  $formData = compact('name', 'email', 'phone', 'subject', 'message');

  $stmt = mysqli_prepare(
    $conn,
    "INSERT INTO messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)"
  );
  mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $phone, $subject, $message);

  if (mysqli_stmt_execute($stmt)) {
    $success = "Your message has been sent successfully! We'll get back to you soon.";
    $formData = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
  } else {
    $error = "Something went wrong. Please try again.";
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div class="container py-5">

    <div class="text-center mb-5">
      <h2 class="fw-bold">Contact Us</h2>
      <p class="text-muted">We'd love to hear from you! Fill out the form below and we'll respond soon.</p>
    </div>

    <div class="row g-5 justify-content-center">

      <!-- Contact Info Sidebar -->
      <div class="col-md-4 col-lg-3">
        <div class="p-3">
          <h5 class="fw-bold mb-4">Get In Touch</h5>
          <div class="d-flex gap-3 mb-3">
            <i class="fas fa-envelope text-success fa-lg mt-1"></i>
            <div>
              <div class="fw-semibold small">Email</div>
              <a href="mailto:info@hopebridgebd.org" class="text-muted small text-decoration-none">
                info@hopebridgebd.org
              </a>
            </div>
          </div>
          <div class="d-flex gap-3 mb-3">
            <i class="fas fa-phone text-success fa-lg mt-1"></i>
            <div>
              <div class="fw-semibold small">Phone</div>
              <a href="tel:+8801234567890" class="text-muted small text-decoration-none">
                +880 1234 567890
              </a>
            </div>
          </div>
          <div class="d-flex gap-3">
            <i class="fas fa-map-marker-alt text-success fa-lg mt-1"></i>
            <div>
              <div class="fw-semibold small">Location</div>
              <span class="text-muted small">Feni &amp; Cumilla, Bangladesh</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="col-md-7 col-lg-6">
        <div class="form-card">

          <?php if ($success): ?>
            <div class="alert alert-success py-2"><?php echo $success; ?></div>
          <?php endif; ?>
          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?php echo $error; ?></div>
          <?php endif; ?>

          <form method="post" novalidate>

            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Your full name" required
                  value="<?php echo htmlspecialchars($formData['name']); ?>">
              </div>
              <div class="col-sm-6">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required
                  value="<?php echo htmlspecialchars($formData['email']); ?>">
              </div>
            </div>

            <div class="row g-3 mt-0">
              <div class="col-sm-6 mt-3">
                <label class="form-label" for="phone">Mobile Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="017xxxxxxxx"
                  value="<?php echo htmlspecialchars($formData['phone']); ?>">
              </div>
              <div class="col-sm-6 mt-3">
                <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                <input type="text" id="subject" name="subject" class="form-control" placeholder="Message subject"
                  required value="<?php echo htmlspecialchars($formData['subject']); ?>">
              </div>
            </div>

            <div class="mb-3 mt-3">
              <label class="form-label" for="message">Message <span class="text-danger">*</span></label>
              <textarea id="message" name="message" class="form-control" rows="5"
                placeholder="Write your message here..."
                required><?php echo htmlspecialchars($formData['message']); ?></textarea>
            </div>

            <button type="submit" name="send" class="btn btn-success w-100 py-2">
              <i class="fas fa-paper-plane me-2"></i>Send Message
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>