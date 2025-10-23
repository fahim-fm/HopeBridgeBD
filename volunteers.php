<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteers - HopeBridgeBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5 mt-5">
  <h2 class="text-center fw-bold mb-4">Our Volunteers</h2>
  <div class="row g-4">
    <?php
    // Fetch all volunteers
    $query = "SELECT v.*, u.name, u.email, u.phone, u.area 
              FROM volunteers v 
              JOIN users u ON v.user_id = u.id
              ORDER BY u.name ASC";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        while($vol = mysqli_fetch_assoc($result)){
            echo '
            <div class="col-md-4" data-aos="fade-up">
              <div class="card shadow h-100 text-center">
                <img src="uploads/'.$vol['image'].'" class="card-img-top rounded-circle mx-auto mt-3" style="width:120px; height:120px; object-fit:cover;" alt="Volunteer">
                <div class="card-body">
                  <h5 class="card-title">'.$vol['name'].'</h5>
                  <p class="card-text mb-1"><strong>Area:</strong> '.$vol['area'].'</p>
                  <p class="card-text mb-1"><strong>Email:</strong> <a href="mailto:'.$vol['email'].'">'.$vol['email'].'</a></p>
                  <p class="card-text mb-1"><strong>Phone:</strong> <a href="tel:'.$vol['phone'].'">'.$vol['phone'].'</a></p>
                  <p class="card-text"><strong>Status:</strong> '.$vol['availability'].'</p>
                </div>
              </div>
            </div>
            ';
        }
    } else {
        echo '<div class="col-12"><p class="text-center text-muted">No volunteers found.</p></div>';
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
