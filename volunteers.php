<?php
session_start();
include 'db.php';

$result = mysqli_query($conn,
    "SELECT v.*, u.name, u.email, u.phone, u.area
     FROM volunteers v
     JOIN users u ON v.user_id = u.id
     ORDER BY u.name ASC");
$volunteers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteers — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="text-center mb-5" data-aos="fade-up">
    <h2 class="fw-bold">Our Volunteers</h2>
    <p class="text-muted">Meet the dedicated people making a difference in Feni &amp; Cumilla.</p>
  </div>

  <?php if (empty($volunteers)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
      <p>No volunteers registered yet.</p>
    </div>
  <?php else: ?>
  <div class="row g-4">
    <?php foreach ($volunteers as $i => $vol):
      $imgSrc = !empty($vol['image'])
                ? 'uploads/'.htmlspecialchars($vol['image'])
                : 'assets/img/default-profile.png';
      $delay  = ($i % 3) * 100;
    ?>
    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
      <div class="card shadow h-100 text-center border-0">
        <div class="card-body py-4">
          <img src="<?php echo $imgSrc; ?>" class="vol-img" alt="<?php echo htmlspecialchars($vol['name']); ?>" loading="lazy">
          <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($vol['name']); ?></h5>
          <span class="badge <?php echo $vol['availability'] === 'Active' ? 'bg-success' : 'bg-secondary'; ?> mb-3">
            <?php echo htmlspecialchars($vol['availability']); ?>
          </span>
          <ul class="list-unstyled text-start small text-muted mt-2">
            <li class="mb-1">
              <i class="fas fa-map-marker-alt text-success me-2"></i>
              <?php echo htmlspecialchars($vol['area']); ?>
            </li>
            <li class="mb-1">
              <i class="fas fa-envelope text-success me-2"></i>
              <a href="mailto:<?php echo htmlspecialchars($vol['email']); ?>" class="text-muted">
                <?php echo htmlspecialchars($vol['email']); ?>
              </a>
            </li>
            <li>
              <i class="fas fa-phone text-success me-2"></i>
              <a href="tel:<?php echo htmlspecialchars($vol['phone']); ?>" class="text-muted">
                <?php echo htmlspecialchars($vol['phone']); ?>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ once: true, duration: 650 });</script>
</body>
</html>
