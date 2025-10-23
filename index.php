<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food & Clothes Donation - Bangladesh</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
 <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

  <script src="https://kit.fontawesome.com/a2d9d6e76b.js" crossorigin="anonymous"></script>
  //fab icon

</head>
<body>

<?php include 'navbar.php'; ?>

<!-- 🔹 Hero Slideshow -->
<div id="heroCarousel" class="carousel slide mt-5" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/img/im2.jpg" class="d-block w-100" alt="Helping Hands">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="fw-bold">Donate Food & Clothes</h2>
        <p>Together we can bring smiles in Feni & Cumilla.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/img/im3.jpeg" class="d-block w-100" alt="Clothes Donation">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="fw-bold">Be A Volunteer</h2>
        <p>Join us to reach the needy faster.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
<!-- 🔹 Central Animated Donate Now Button -->
<div class="text-center my-4">
    <a href="login.php" class="btn btn-warning btn-lg fw-bold donate-btn px-5 py-3 shadow-lg">
       Donate Now
    </a>
    <p class="mt-3 fw-semibold text-muted" data-aos="fade-up">
        “A small act of kindness can create a ripple of hope. Together, let’s fight hunger and bring warmth to lives.”
    </p>
</div>

<!-- 🔹 Why Donate Section -->
<section class="container py-5">
  <div class="row">
    <div class="col-md-12 text-center mb-4">
      <h2 class="fw-bold text-success" data-aos="fade-up">Why Donate?</h2>
      <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
        Every day, thousands of families in Feni and Cumilla struggle with basic needs. 
        Your contribution of food or clothes can bring relief, dignity, and hope to the less fortunate. 
        By donating, you are not only helping someone survive today but also inspiring a better tomorrow.
      </p>
    </div>
  </div>
  <div class="row text-center">
    <div class="col-md-4" data-aos="zoom-in">
      <i class="fas fa-utensils fa-3x text-success mb-3"></i>
      <h5 class="fw-bold">Fight Hunger</h5>
      <p class="text-muted">Your food donations ensure no one sleeps hungry in our communities.</p>
    </div>
    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
      <i class="fas fa-tshirt fa-3x text-warning mb-3"></i>
      <h5 class="fw-bold">Spread Warmth</h5>
      <p class="text-muted">Clothes and essentials bring comfort, protection, and dignity to families in need.</p>
    </div>
    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="400">
      <i class="fas fa-hands-helping fa-3x text-danger mb-3"></i>
      <h5 class="fw-bold">Build Community</h5>
      <p class="text-muted">Donors and volunteers together create a stronger, more compassionate society.</p>
    </div>
  </div>
</section>


<!-- 🔹 Latest Donations -->
<section class="container py-5">
  <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Latest Donations</h2>
  <div class="row g-4">
    <?php
      $query = "SELECT * FROM donations ORDER BY created_at DESC LIMIT 6";
      $result = mysqli_query($conn, $query);
      while($row = mysqli_fetch_assoc($result)){
        echo '
          <div class="col-md-4" data-aos="zoom-in">
            <div class="card shadow h-100">
              <img src="uploads/'.$row['image'].'" class="card-img-top" alt="Donation">
              <div class="card-body">
                <h5 class="card-title">'.$row['title'].'</h5>
                <p class="card-text text-muted">'.$row['category'].' - '.$row['area'].'</p>
                <a href="donation_details.php?id='.$row['id'].'" class="btn btn-success btn-sm">View Details</a>
              </div>
            </div>
          </div>
        ';
      }
    ?>
  </div>
</section>

<!-- 🔹 Impact Counter -->
<?php
// Count total food donations
$foodCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations WHERE category IN ('Cooked Food','Dry Food')"))['total'];

// Count total clothes donations
$clothesCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations WHERE category IN ('Clothes','Winter Items')"))['total'];

// Count total beneficiaries (optional: number of claimed/delivered donations)
$beneficiariesCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations WHERE status='Delivered' OR status='Claimed'"))['total'];
?>

<section class="bg-light py-5 text-center">
  <div class="container">
    <div class="row">
      <div class="col-md-4" data-aos="fade-up"><h2 class="text-success"><?php echo $foodCount; ?></h2><p>Food Donations</p></div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200"><h2 class="text-warning"><?php echo $clothesCount; ?></h2><p>Clothes Donated</p></div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="400"><h2 class="text-danger"><?php echo $beneficiariesCount; ?></h2><p>delivered</p></div>
    </div>
  </div>
</section>
<!-- 🔹 Closing Motivation -->
<section class="bg-success text-white py-5 text-center">
  <div class="container">
    <h2 class="fw-bold" data-aos="fade-up">Together, We Can Change Lives</h2>
    <p class="lead" data-aos="fade-up" data-aos-delay="200">
      Join HopeBridge in building a bridge of kindness. Your small step today can light up someone’s tomorrow.
    </p>
    <a href="login.php" class="btn btn-warning btn-lg mt-3 fw-bold shadow">Start Donating</a>
  </div>
</section>

<!-- 🔹 Footer -->
<footer class="bg-dark text-white pt-5 pb-3">
  <div class="container">
    <div class="row">

      <!-- About Section -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold">HopeBridge</h5>
        <p>Connecting donors and volunteers to help the needy in Feni & Cumilla. Together, we can make a difference!</p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-3 mb-4">
        <h5 class="fw-bold">Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
          <li><a href="donations.php" class="text-white text-decoration-none">Donations</a></li>
          <li><a href="volunteers.php" class="text-white text-decoration-none">Volunteers</a></li>
          <li><a href="login.php" class="text-white text-decoration-none">Donate Now</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-md-5 mb-4">
        <h5 class="fw-bold">Contact Us</h5>
        <p><i class="fas fa-envelope me-2"></i> info@hopebridge.org</p>
        <p><i class="fas fa-phone me-2"></i> +880 1234 567890</p>
        <p><i class="fas fa-map-marker-alt me-2"></i> Feni & Cumilla, Bangladesh</p>

        <!-- Social Media -->
        <div class="mt-2">
          <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
          <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
        </div>
      </div>

    </div>

    <hr class="border-light">

    <div class="text-center pt-3">
      <p class="mb-0">&copy; 2025 HopeBridge. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.6.2/countUp.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const foodCounter = new CountUp('foodCounter', <?php echo $foodCount; ?>);
    const clothesCounter = new CountUp('clothesCounter', <?php echo $clothesCount; ?>);
    const beneficiariesCounter = new CountUp('beneficiariesCounter', <?php echo $beneficiariesCount; ?>);
    
    if (!foodCounter.error) foodCounter.start();
    if (!clothesCounter.error) clothesCounter.start();
    if (!beneficiariesCounter.error) beneficiariesCounter.start();
});
</script>

</body>
</html>
