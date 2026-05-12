<?php
// admin/_navbar.php — include inside admin pages after session/auth check
$currentPage = basename($_SERVER['PHP_SELF']);
function adminNavActive($file, $current) {
    return ($file === $current) ? 'active fw-semibold' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">
      <img src="../assets/img/logo.png" alt="HopeBridgeBD" width="90" height="36" loading="lazy"> Admin
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo adminNavActive('manage_donors.php', $currentPage); ?>"
             href="manage_donors.php">Donors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo adminNavActive('manage_donations.php', $currentPage); ?>"
             href="manage_donations.php">Donations</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo adminNavActive('manage_volunteers.php', $currentPage); ?>"
             href="manage_volunteers.php">Volunteers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo adminNavActive('admin_messages.php', $currentPage); ?>"
             href="admin_messages.php">Messages</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo adminNavActive('reports.php', $currentPage); ?>"
             href="reports.php">Reports</a>
        </li>
        <li class="nav-item ms-lg-2">
          <a class="nav-link btn btn-warning text-dark btn-sm px-3"
             href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
