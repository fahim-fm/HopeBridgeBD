<?php
include 'db.php';

// Handle filters
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$areaFilter = isset($_GET['area']) ? $_GET['area'] : '';

$whereClauses = [];
if($categoryFilter) $whereClauses[] = "d.category='$categoryFilter'";
if($areaFilter) $whereClauses[] = "d.area='$areaFilter'";

$whereSQL = '';
if(count($whereClauses) > 0){
    $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
}

// Fetch donations
$query = "SELECT d.*, u.name AS donor_name FROM donations d 
          JOIN users u ON d.donor_id = u.id 
          $whereSQL
          ORDER BY d.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donations - HopeBridge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5 mt-5">
  <h2 class="text-center fw-bold mb-4">Available Donations</h2>

  <!-- Filters -->
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
      <select name="category" class="form-select">
        <option value="">All Categories</option>
        <option value="Cooked Food" <?php if($categoryFilter=="Cooked Food") echo "selected"; ?>>Cooked Food</option>
        <option value="Dry Food" <?php if($categoryFilter=="Dry Food") echo "selected"; ?>>Dry Food</option>
        <option value="Clothes" <?php if($categoryFilter=="Clothes") echo "selected"; ?>>Clothes</option>
        <option value="Winter Items" <?php if($categoryFilter=="Winter Items") echo "selected"; ?>>Winter Items</option>
        <option value="Stationery/Books" <?php if($categoryFilter=="Stationery/Books") echo "selected"; ?>>Stationery/Books</option>
      </select>
    </div>
    <div class="col-md-4">
      <select name="area" class="form-select">
        <option value="">All Areas</option>
        <option value="Feni" <?php if($areaFilter=="Feni") echo "selected"; ?>>Feni</option>
        <option value="Cumilla" <?php if($areaFilter=="Cumilla") echo "selected"; ?>>Cumilla</option>
      </select>
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-success w-100">Filter</button>
    </div>
  </form>

  <!-- Donations List -->
  <div class="row g-4">
    <?php
    if(mysqli_num_rows($result) > 0){
        while($don = mysqli_fetch_assoc($result)){
            echo '
            <div class="col-md-4" data-aos="fade-up">
              <div class="card shadow h-100">
                <img src="uploads/'.$don['image'].'" class="card-img-top" style="height:200px; object-fit:cover;" alt="Donation">
                <div class="card-body">
                  <h5 class="card-title">'.$don['title'].'</h5>
                  <p class="card-text text-muted mb-1"><strong>Category:</strong> '.$don['category'].'</p>
                  <p class="card-text text-muted mb-1"><strong>Area:</strong> '.$don['area'].'</p>
                  <p class="card-text text-muted mb-1"><strong>Status:</strong> '.$don['status'].'</p>
                  <p class="card-text text-muted mb-2"><strong>Donor:</strong> '.$don['donor_name'].'</p>
                  <a href="donation_details.php?id='.$don['id'].'" class="btn btn-success btn-sm">View Details</a>
                </div>
              </div>
            </div>
            ';
        }
    } else {
        echo '<div class="col-12"><p class="text-center text-muted">No donations found.</p></div>';
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
