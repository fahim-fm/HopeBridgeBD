<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

if (isset($_GET['delete'])) {
  $del_id = (int) $_GET['delete'];
  $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'donor'");
  mysqli_stmt_bind_param($stmt, 'i', $del_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: manage_donors.php");
  exit;
}

$donors = mysqli_query(
  $conn,
  "SELECT * FROM users WHERE role='donor' ORDER BY created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Donors — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <h3 class="fw-bold mb-4">Manage Donors</h3>

    <div class="admin-table-wrap">
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-success">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Area</th>
            <th>Registered</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          while ($donor = mysqli_fetch_assoc($donors)): ?>
            <tr>
              <td class="text-muted small"><?php echo $i++; ?></td>
              <td class="fw-semibold"><?php echo htmlspecialchars($donor['name']); ?></td>
              <td><a
                  href="mailto:<?php echo htmlspecialchars($donor['email']); ?>"><?php echo htmlspecialchars($donor['email']); ?></a>
              </td>
              <td><?php echo htmlspecialchars($donor['phone']); ?></td>
              <td><?php echo htmlspecialchars($donor['area']); ?></td>
              <td class="small text-muted"><?php echo date('d M Y', strtotime($donor['created_at'])); ?></td>
              <td>
                <div class="d-flex gap-1">
                  <a href="edit_donor.php?id=<?php echo (int) $donor['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                  <a href="manage_donors.php?delete=<?php echo (int) $donor['id']; ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this donor?')">Delete</a>
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