<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

if (isset($_GET['delete'])) {
  $del = (int) $_GET['delete'];
  $stmt = mysqli_prepare($conn, "DELETE FROM messages WHERE id = ?");
  mysqli_stmt_bind_param($stmt, 'i', $del);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: admin_messages.php");
  exit;
}

$result = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
$msgCount = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages — Admin</title>
  <link rel="icon" type="image/png" href="../favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <?php include '_navbar.php'; ?>

  <div class="container py-5">
    <h3 class="fw-bold mb-4">
      Contact Messages
      <span class="badge bg-secondary fs-6 align-middle ms-1"><?php echo $msgCount; ?></span>
    </h3>

    <?php if ($msgCount === 0): ?>
      <div class="alert alert-info">No messages found.</div>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Received</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td class="fw-semibold"><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                  <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>">
                    <?php echo htmlspecialchars($row['email']); ?>
                  </a>
                </td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td style="max-width:320px; white-space:pre-wrap; font-size:.88rem">
                  <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                </td>
                <td class="small text-muted text-nowrap">
                  <?php echo date('d M Y, g:i A', strtotime($row['created_at'])); ?>
                </td>
                <td>
                  <a href="admin_messages.php?delete=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this message?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HopeBridgeBD | Admin</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>