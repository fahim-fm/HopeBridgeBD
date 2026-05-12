<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
  header("Location: login.php");
  exit;
}

$user_id = (int) $_SESSION['user_id'];
$name = htmlspecialchars($_SESSION['name']);

$stmt = mysqli_prepare(
  $conn,
  "SELECT * FROM donations WHERE donor_id = ? ORDER BY created_at DESC"
);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$total = count($rows);
$available = array_filter($rows, fn($r) => $r['status'] === 'Available');
$delivered = array_filter($rows, fn($r) => $r['status'] === 'Delivered');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Dashboard — HopeBridgeBD</title>
  <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <!-- Dashboard Navbar -->
  <nav class="navbar navbar-dark bg-success shadow">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">
        <img src="assets/img/logo.png" alt="HopeBridgeBD" height="34" loading="lazy">
      </a>
      <div class="d-flex align-items-center gap-2">
        <span class="text-white d-none d-sm-inline small">Hi, <?php echo $name; ?></span>
        <a href="index.php" class="btn btn-outline-light btn-sm">Home</a>
        <a href="logout.php" class="btn btn-warning btn-sm fw-semibold">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container py-4">

    <h4 class="fw-bold mb-1">Welcome back, <?php echo $name; ?>!</h4>
    <p class="text-muted mb-4">Manage your donation listings below.</p>

    <!-- Stats -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-4">
        <div class="card text-center border-0 shadow-sm h-100 py-3">
          <div class="card-body p-2">
            <h3 class="text-success fw-bold mb-0"><?php echo $total; ?></h3>
            <small class="text-muted">Total Donations</small>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4">
        <div class="card text-center border-0 shadow-sm h-100 py-3">
          <div class="card-body p-2">
            <h3 class="text-warning fw-bold mb-0"><?php echo count($available); ?></h3>
            <small class="text-muted">Available</small>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4">
        <div class="card text-center border-0 shadow-sm h-100 py-3">
          <div class="card-body p-2">
            <h3 class="text-primary fw-bold mb-0"><?php echo count($delivered); ?></h3>
            <small class="text-muted">Delivered</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">My Donations</h5>
      <a href="donation_form.php" class="btn btn-success btn-sm">
        <i class="fas fa-plus me-1"></i> Add New
      </a>
    </div>

    <!-- Table -->
    <?php if (empty($rows)): ?>
      <div class="text-center py-5 text-muted">
        <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
        <p>You haven't made any donations yet.</p>
        <a href="donation_form.php" class="btn btn-success">Make Your First Donation</a>
      </div>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-success">
            <tr>
              <th>Title</th>
              <th>Category</th>
              <th>Status</th>
              <th>Image</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row):
              $statusClass = match ($row['status']) {
                'Claimed' => 'badge-claimed',
                'Delivered' => 'badge-delivered',
                default => 'badge-available',
              };
              ?>
              <tr>
                <td class="fw-semibold"><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>
                  <span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
                </td>
                <td>
                  <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="70" class="rounded"
                      alt="<?php echo htmlspecialchars($row['title']); ?>">
                  <?php else: ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                </td>
                <td class="small text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td>
                  <div class="d-flex gap-1 flex-wrap">
                    <a href="donation_form.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_donation.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-danger"
                      onclick="return confirm('Delete this donation?')">Delete</a>
                  </div>
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