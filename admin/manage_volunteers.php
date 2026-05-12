<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

if (isset($_GET['delete'])) {
  $del = (int) $_GET['delete'];
  $stmt = mysqli_prepare($conn, "DELETE FROM volunteers WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'i', $del);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: manage_volunteers.php");
  exit;
}

$volunteers = mysqli_query(
  $conn,
  "SELECT v.*, u.name AS volunteer_name, u.email AS volunteer_email,
            u.phone AS volunteer_phone, u.area AS volunteer_area
     FROM volunteers v
     JOIN users u ON v.user_id = u.id
     ORDER BY v.id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Volunteers — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h3 class="fw-bold mb-0">Manage Volunteers</h3>
      <a href="add_volunteer.php" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Add Volunteer
      </a>
    </div>

    <div class="admin-table-wrap">
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-info">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Area</th>
            <th>Availability</th>
            <th>Photo</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($v = mysqli_fetch_assoc($volunteers)): ?>
            <tr>
              <td class="fw-semibold"><?php echo htmlspecialchars($v['volunteer_name']); ?></td>
              <td><a
                  href="mailto:<?php echo htmlspecialchars($v['volunteer_email']); ?>"><?php echo htmlspecialchars($v['volunteer_email']); ?></a>
              </td>
              <td><?php echo htmlspecialchars($v['volunteer_phone']); ?></td>
              <td><?php echo htmlspecialchars($v['volunteer_area']); ?></td>
              <td>
                <span class="badge <?php echo $v['availability'] === 'Active' ? 'bg-success' : 'bg-secondary'; ?>">
                  <?php echo htmlspecialchars($v['availability']); ?>
                </span>
              </td>
              <td>
                <?php if ($v['image']): ?>
                  <img src="../uploads/<?php echo htmlspecialchars($v['image']); ?>" width="50" class="rounded-circle"
                    alt="">
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="edit_volunteer.php?id=<?php echo (int) $v['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                  <a href="manage_volunteers.php?delete=<?php echo (int) $v['id']; ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this volunteer?')">Delete</a>
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