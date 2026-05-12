<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
  header("Location: login.php");
  exit;
}

$volunteer_id = (int) $_SESSION['user_id'];
$name = htmlspecialchars($_SESSION['name']);

// Volunteer profile
$stmt = mysqli_prepare(
  $conn,
  "SELECT u.*, v.image, v.availability
     FROM users u
     LEFT JOIN volunteers v ON u.id = v.user_id
     WHERE u.id = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, 'i', $volunteer_id);
mysqli_stmt_execute($stmt);
$volunteer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

// Donations in volunteer's area — JOIN to get donor name (no N+1)
$stmt2 = mysqli_prepare(
  $conn,
  "SELECT d.*, u.name AS donor_name
     FROM donations d
     JOIN users u ON d.donor_id = u.id
     WHERE d.area = ? AND d.status = 'Available'
     ORDER BY d.created_at DESC"
);
$area = $volunteer['area'] ?? '';
mysqli_stmt_bind_param($stmt2, 's', $area);
mysqli_stmt_execute($stmt2);
$donations = mysqli_fetch_all(mysqli_stmt_get_result($stmt2), MYSQLI_ASSOC);
mysqli_stmt_close($stmt2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volunteer Dashboard — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <nav class="navbar navbar-dark bg-success shadow">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">
        <img src="assets/img/logo.png" alt="HopeBridgeBD"width="90" height="36" loading="lazy">
      </a>
      <div class="d-flex align-items-center gap-2">
        <span class="text-white d-none d-sm-inline small">Hi, <?php echo $name; ?></span>
        <a href="index.php" class="btn btn-outline-light btn-sm">Home</a>
        <a href="logout.php" class="btn btn-warning btn-sm fw-semibold">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container py-4">

    <!-- Profile Card -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body text-center py-4">
        <?php
        $imgSrc = !empty($volunteer['image'])
          ? 'uploads/' . htmlspecialchars($volunteer['image'])
          : 'assets/img/default-profile.png';
        ?>
        <img src="<?php echo $imgSrc; ?>" alt="Profile" class="profile-img mb-2">
        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($volunteer['name']); ?></h5>
        <p class="text-muted small mb-1"><?php echo htmlspecialchars($volunteer['email']); ?></p>
        <div class="d-flex justify-content-center flex-wrap gap-2 mt-2">
          <span class="badge bg-secondary">
            <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($volunteer['phone']); ?>
          </span>
          <span class="badge bg-success">
            <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($volunteer['area']); ?>
          </span>
          <span
            class="badge <?php echo ($volunteer['availability'] ?? '') === 'Active' ? 'bg-info text-dark' : 'bg-secondary'; ?>">
            <?php echo ucfirst($volunteer['availability'] ?? 'Active'); ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Donations in Area -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Available Donations in <?php echo htmlspecialchars($area); ?></h5>
      <span class="badge bg-success"><?php echo count($donations); ?></span>
    </div>

    <?php if (empty($donations)): ?>
      <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
        <p>No available donations in your area right now.</p>
      </div>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-success">
            <tr>
              <th>Title</th>
              <th>Category</th>
              <th>Donor</th>
              <th>Area</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($donations as $row): ?>
              <tr>
                <td class="fw-semibold"><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['area']); ?></td>
                <td class="small text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td>
                  <a href="donation_details.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-success">View</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>