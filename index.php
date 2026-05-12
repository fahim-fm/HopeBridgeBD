<?php
session_start();
include 'db.php';

// Impact counts (single query for efficiency)
$foodCount = (int) mysqli_fetch_assoc(mysqli_query(
  $conn,
  "SELECT COUNT(*) AS t FROM donations WHERE category IN ('Cooked Food','Dry Food')"
))['t'];
$clothesCount = (int) mysqli_fetch_assoc(mysqli_query(
  $conn,
  "SELECT COUNT(*) AS t FROM donations WHERE category IN ('Clothes','Winter Items')"
))['t'];
$beneficiariesCount = (int) mysqli_fetch_assoc(mysqli_query(
  $conn,
  "SELECT COUNT(*) AS t FROM donations WHERE status IN ('Delivered','Claimed')"
))['t'];

// Latest donations
$latestResult = mysqli_query($conn, "SELECT * FROM donations ORDER BY created_at DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="HopeBridgeBD — Donate food &amp; clothes to families in Feni &amp; Cumilla, Bangladesh.">
  <title>HopeBridgeBD — Food &amp; Clothes Donation</title>

  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
  <!-- Font Awesome (icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plh7eecIS333LgRFBtIGhBlM7u9vBQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <!-- ─── Hero Carousel ─── -->
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
        aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/img/im2.jpg" class="d-block w-100" alt="Helping Hands" loading="eager">
        <div class="carousel-caption">
          <h2 class="fw-bold">Donate Food &amp; Clothes</h2>
          <p class="d-none d-sm-block">Together we can bring smiles in Feni &amp; Cumilla.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/im3.jpeg" class="d-block w-100" alt="Clothes Donation" loading="lazy">
        <div class="carousel-caption">
          <h2 class="fw-bold">Be A Volunteer</h2>
          <p class="d-none d-sm-block">Join us to reach the needy faster.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- ─── Donate Now CTA ─── -->
  <div class="text-center my-5 px-3">
    <a href="<?php echo isset($_SESSION['user_id']) ? 'donation_form.php' : 'login.php'; ?>"
      class="btn btn-warning btn-lg fw-bold donate-btn px-4 px-md-5 py-3 shadow-lg">
      Donate Now
    </a>
    <p class="mt-4 fw-semibold text-muted mx-auto" style="max-width:600px" data-aos="fade-up">
      "A small act of kindness can create a ripple of hope. Together, let's fight hunger and bring warmth to lives."
    </p>
  </div>

  <!-- ─── Why Donate ─── -->
  <section class="container py-5">
    <div class="text-center mb-4" data-aos="fade-up">
      <h2 class="fw-bold text-success">Why Donate?</h2>
      <p class="text-muted mx-auto" style="max-width:680px">
        Every day, thousands of families in Feni and Cumilla struggle with basic needs.
        Your contribution of food or clothes can bring relief, dignity, and hope to the less fortunate.
      </p>
    </div>
    <div class="row text-center g-4">
      <div class="col-sm-6 col-md-4" data-aos="zoom-in">
        <div class="p-4">
          <i class="fas fa-utensils fa-3x text-success mb-3"></i>
          <h5 class="fw-bold">Fight Hunger</h5>
          <p class="text-muted">Your food donations ensure no one sleeps hungry in our communities.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4" data-aos="zoom-in" data-aos-delay="150">
        <div class="p-4">
          <i class="fas fa-shirt fa-3x text-warning mb-3"></i>
          <h5 class="fw-bold">Spread Warmth</h5>
          <p class="text-muted">Clothes and essentials bring comfort, protection, and dignity to families in need.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="p-4">
          <i class="fas fa-hands-helping fa-3x text-danger mb-3"></i>
          <h5 class="fw-bold">Build Community</h5>
          <p class="text-muted">Donors and volunteers together create a stronger, more compassionate society.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ─── Latest Donations ─── -->
  <section class="container py-5">
    <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Latest Donations</h2>
    <div class="row g-4">
      <?php if (mysqli_num_rows($latestResult) > 0):
        while ($row = mysqli_fetch_assoc($latestResult)):
          $imgSrc = !empty($row['image']) ? 'uploads/' . htmlspecialchars($row['image']) : 'assets/img/no-image.png';
          ?>
          <div class="col-sm-6 col-md-4" data-aos="zoom-in">
            <div class="card shadow h-100">
              <img src="<?php echo $imgSrc; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>"
                loading="lazy">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($row['category']); ?> —
                  <?php echo htmlspecialchars($row['area']); ?>
                </p>
                <a href="donation_details.php?id=<?php echo (int) $row['id']; ?>"
                  class="btn btn-success btn-sm mt-2 align-self-start">View Details</a>
              </div>
            </div>
          </div>
        <?php endwhile;
      else: ?>
        <div class="col-12 text-center text-muted">No donations yet.</div>
      <?php endif; ?>
    </div>
    <div class="text-center mt-4">
      <a href="donations.php" class="btn btn-outline-success px-4">View All Donations</a>
    </div>
  </section>

  <!-- ─── Impact Counter ─── -->
  <section class="bg-light py-5 text-center impact-section">
    <div class="container">
      <h2 class="fw-bold mb-4" data-aos="fade-up">Our Impact</h2>
      <div class="row g-4">
        <div class="col-sm-4" data-aos="fade-up">
          <h2 class="text-success fw-bold" id="foodCounter">0</h2>
          <p class="text-muted mb-0">Food Donations</p>
        </div>
        <div class="col-sm-4" data-aos="fade-up" data-aos-delay="150">
          <h2 class="text-warning fw-bold" id="clothesCounter">0</h2>
          <p class="text-muted mb-0">Clothes Donated</p>
        </div>
        <div class="col-sm-4" data-aos="fade-up" data-aos-delay="300">
          <h2 class="text-danger fw-bold" id="beneficiariesCounter">0</h2>
          <p class="text-muted mb-0">Delivered</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ─── Closing CTA ─── -->
  <section class="bg-success text-white py-5 text-center">
    <div class="container">
      <h2 class="fw-bold" data-aos="fade-up">Together, We Can Change Lives</h2>
      <p class="lead mt-2" data-aos="fade-up" data-aos-delay="150">
        Join HopeBridgeBD in building a bridge of kindness. Your small step today can light up someone's tomorrow.
      </p>
      <a href="<?php echo isset($_SESSION['user_id']) ? 'donation_form.php' : 'login.php'; ?>"
        class="btn btn-warning btn-lg mt-3 fw-bold shadow px-5">Start Donating</a>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({ once: true, duration: 700 });

// PURE JS COUNTER (NO LIBRARY = NO ERROR EVER)
function animate(id, target) {
  let el = document.getElementById(id);
  let count = 0;
  let step = Math.ceil(target / 100);

  let timer = setInterval(() => {
    count += step;

    if (count >= target) {
      count = target;
      clearInterval(timer);
    }

    el.innerText = count;
  }, 20);
}

window.addEventListener("DOMContentLoaded", () => {

  const section = document.querySelector(".impact-section");
  let started = false;

  const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && !started) {
      started = true;

      animate("foodCounter", <?php echo $foodCount; ?>);
      animate("clothesCounter", <?php echo $clothesCount; ?>);
      animate("beneficiariesCounter", <?php echo $beneficiariesCount; ?>);
    }
  }, { threshold: 0.3 });

  if (section) observer.observe(section);

});
</script>
</body>

</html>