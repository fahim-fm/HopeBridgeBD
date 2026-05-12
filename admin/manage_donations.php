<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$allowedStatuses = ['Available', 'Claimed', 'Delivered'];

// Delete
if (isset($_GET['delete'])) {
  $del = (int) $_GET['delete'];
  $stmt = mysqli_prepare($conn, "DELETE FROM donations WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'i', $del);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: manage_donations.php");
  exit;
}

// Status update
if (isset($_GET['status'], $_GET['id']) && in_array($_GET['status'], $allowedStatuses)) {
  $id = (int) $_GET['id'];
  $status = $_GET['status'];
  $stmt = mysqli_prepare($conn, "UPDATE donations SET status = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'si', $status, $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: manage_donations.php");
  exit;
}

$donations = mysqli_query(
  $conn,
  "SELECT d.*, u.name AS donor_name, u.email AS donor_email
     FROM donations d JOIN users u ON d.donor_id = u.id
     ORDER BY d.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Donations — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <h3 class="fw-bold mb-4">Manage Donations</h3>

    <div class="admin-table-wrap">
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-warning">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Donor</th>
            <th>Area</th>
            <th>Status</th>
            <th>Image</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          while ($d = mysqli_fetch_assoc($donations)):
            $sc = match ($d['status']) {
              'Claimed' => 'badge-claimed',
              'Delivered' => 'badge-delivered',
              default => 'badge-available',
            };
            ?>
            <tr>
              <td class="small text-muted"><?php echo $i++; ?></td>
              <td class="fw-semibold"><?php echo htmlspecialchars($d['title']); ?></td>
              <td><?php echo htmlspecialchars($d['category']); ?></td>
              <td>
                <?php echo htmlspecialchars($d['donor_name']); ?>
                <br><small class="text-muted"><?php echo htmlspecialchars($d['donor_email']); ?></small>
              </td>
              <td><?php echo htmlspecialchars($d['area']); ?></td>
              <td><span class="status-badge <?php echo $sc; ?>"><?php echo $d['status']; ?></span></td>
              <td>
                <?php if ($d['image']): ?>
                  <img src="../uploads/<?php echo htmlspecialchars($d['image']); ?>" width="70" class="rounded">
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
              <td class="small text-muted"><?php echo date('d M Y', strtotime($d['created_at'])); ?></td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  <a href="manage_donations.php?id=<?php echo (int) $d['id']; ?>&status=Available"
                    class="btn btn-sm btn-success">Available</a>
                  <a href="manage_donations.php?id=<?php echo (int) $d['id']; ?>&status=Claimed"
                    class="btn btn-sm btn-warning text-dark">Claimed</a>
                  <a href="manage_donations.php?id=<?php echo (int) $d['id']; ?>&status=Delivered"
                    class="btn btn-sm btn-primary">Delivered</a>
                  <a href="manage_donations.php?delete=<?php echo (int) $d['id']; ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this donation?')">Delete</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HopeBridgeBD | Admin</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>