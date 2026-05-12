<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$total_donations = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM donations"))['t'];
$total_donors = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM users WHERE role='donor'"))['t'];
$total_volunteers = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM volunteers"))['t'];
$total_delivered = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM donations WHERE status='Delivered'"))['t'];

// Category breakdown
$catResult = mysqli_query(
  $conn,
  "SELECT category, COUNT(*) AS cnt FROM donations GROUP BY category ORDER BY cnt DESC"
);

// Recent donations
$recentResult = mysqli_query(
  $conn,
  "SELECT d.*, u.name AS donor_name
     FROM donations d JOIN users u ON d.donor_id = u.id
     ORDER BY d.created_at DESC LIMIT 8"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <h3 class="fw-bold mb-4">Platform Reports</h3>

    <!-- Stats Cards -->
    <div class="row g-3 mb-5">
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="card-body p-2">
            <i class="fas fa-box-open fa-2x text-success mb-2"></i>
            <h3 class="fw-bold text-success"><?php echo $total_donations; ?></h3>
            <small class="text-muted">Total Donations</small>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="card-body p-2">
            <i class="fas fa-users fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold text-primary"><?php echo $total_donors; ?></h3>
            <small class="text-muted">Total Donors</small>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="card-body p-2">
            <i class="fas fa-hands-helping fa-2x text-warning mb-2"></i>
            <h3 class="fw-bold text-warning"><?php echo $total_volunteers; ?></h3>
            <small class="text-muted">Volunteers</small>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
          <div class="card-body p-2">
            <i class="fas fa-check-circle fa-2x text-danger mb-2"></i>
            <h3 class="fw-bold text-danger"><?php echo $total_delivered; ?></h3>
            <small class="text-muted">Delivered</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Breakdown + Recent Donations -->
    <div class="row g-4">

      <!-- Category Breakdown -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white fw-bold border-bottom">Donations by Category</div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              <?php while ($cat = mysqli_fetch_assoc($catResult)):
                $pct = $total_donations > 0 ? round($cat['cnt'] / $total_donations * 100) : 0;
                ?>
                <li class="list-group-item">
                  <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-semibold"><?php echo htmlspecialchars($cat['category']); ?></span>
                    <span class="small text-muted"><?php echo $cat['cnt']; ?> (<?php echo $pct; ?>%)</span>
                  </div>
                  <div class="progress" style="height:6px">
                    <div class="progress-bar bg-success" style="width:<?php echo $pct; ?>%"></div>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Recent Donations Table -->
      <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white fw-bold border-bottom">Recent Donations</div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Donor</th>
                    <th>Status</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($don = mysqli_fetch_assoc($recentResult)):
                    $sc = match ($don['status']) {
                      'Claimed' => 'badge-claimed',
                      'Delivered' => 'badge-delivered',
                      default => 'badge-available',
                    };
                    ?>
                    <tr>
                      <td class="fw-semibold small"><?php echo htmlspecialchars($don['title']); ?></td>
                      <td class="small"><?php echo htmlspecialchars($don['category']); ?></td>
                      <td class="small"><?php echo htmlspecialchars($don['donor_name']); ?></td>
                      <td><span class="status-badge <?php echo $sc; ?>"><?php echo $don['status']; ?></span></td>
                      <td class="small text-muted text-nowrap">
                        <?php echo date('d M Y', strtotime($don['created_at'])); ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HopeBridgeBD | Admin</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>