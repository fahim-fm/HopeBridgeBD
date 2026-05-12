<?php
session_start();
include 'db.php';

// Safe filter values (whitelist)
$allowedCategories = ['Cooked Food', 'Dry Food', 'Clothes', 'Winter Items', 'Stationery/Books'];
$allowedAreas = ['Feni', 'Cumilla'];

$categoryFilter = in_array($_GET['category'] ?? '', $allowedCategories) ? $_GET['category'] : '';
$areaFilter = in_array($_GET['area'] ?? '', $allowedAreas) ? $_GET['area'] : '';

// Build query with prepared statement
$where = [];
$types = '';
$params = [];

if ($categoryFilter) {
  $where[] = 'd.category = ?';
  $types .= 's';
  $params[] = $categoryFilter;
}
if ($areaFilter) {
  $where[] = 'd.area = ?';
  $types .= 's';
  $params[] = $areaFilter;
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT d.*, u.name AS donor_name
        FROM donations d
        JOIN users u ON d.donor_id = u.id
        $whereSQL
        ORDER BY d.created_at DESC";

if ($types) {
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
} else {
  $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donations — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div class="container py-5">
    <h2 class="text-center fw-bold mb-4">Available Donations</h2>

    <!-- Filters -->
    <form method="get" class="row g-2 g-md-3 mb-4 filter-form">
      <div class="col-12 col-sm-5 col-md-4">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          <?php foreach ($allowedCategories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php if ($categoryFilter === $cat)
                 echo 'selected'; ?>>
              <?php echo $cat; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-sm-4 col-md-3">
        <select name="area" class="form-select">
          <option value="">All Areas</option>
          <?php foreach ($allowedAreas as $area): ?>
            <option value="<?php echo $area; ?>" <?php if ($areaFilter === $area)
                 echo 'selected'; ?>>
              <?php echo $area; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-sm-3 col-md-2">
        <button type="submit" class="btn btn-success w-100">Filter</button>
      </div>
      <?php if ($categoryFilter || $areaFilter): ?>
        <div class="col-auto">
          <a href="donations.php" class="btn btn-outline-secondary">Clear</a>
        </div>
      <?php endif; ?>
    </form>

    <!-- Donation Cards -->
    <div class="row g-4">
      <?php
      $count = 0;
      while ($don = mysqli_fetch_assoc($result)):
        $count++;
        $imgSrc = !empty($don['image'])
          ? 'uploads/' . htmlspecialchars($don['image'])
          : 'assets/img/no-image.png';

        $statusClass = match ($don['status']) {
          'Claimed' => 'badge-claimed',
          'Delivered' => 'badge-delivered',
          default => 'badge-available',
        };
        ?>
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <div class="card shadow h-100">
            <img src="<?php echo $imgSrc; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($don['title']); ?>"
              loading="lazy">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($don['title']); ?></h5>
              <p class="card-text text-muted small mb-1">
                <strong>Category:</strong> <?php echo htmlspecialchars($don['category']); ?>
              </p>
              <p class="card-text text-muted small mb-1">
                <strong>Area:</strong> <?php echo htmlspecialchars($don['area']); ?>
              </p>
              <p class="card-text small mb-1">
                <strong>Status:</strong>
                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $don['status']; ?></span>
              </p>
              <p class="card-text text-muted small mb-3">
                <strong>Donor:</strong> <?php echo htmlspecialchars($don['donor_name']); ?>
              </p>
              <a href="donation_details.php?id=<?php echo (int) $don['id']; ?>"
                class="btn btn-success btn-sm mt-auto align-self-start">View Details</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>

      <?php if ($count === 0): ?>
        <div class="col-12 text-center py-5">
          <p class="text-muted fs-5">No donations
            found<?php echo ($categoryFilter || $areaFilter) ? ' for the selected filters.' : '.'; ?></p>
          <?php if ($categoryFilter || $areaFilter): ?>
            <a href="donations.php" class="btn btn-outline-success mt-2">View All Donations</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init({ once: true, duration: 600 });</script>
</body>

</html>