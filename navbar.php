<?php
// Session must already be started by the including file
$isLoggedIn = isset($_SESSION['user_id']);
$userRole   = $isLoggedIn ? $_SESSION['role'] : '';
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['name']) : '';

// Build dashboard link based on role
$dashboardLink = '#';
if ($userRole === 'donor')     $dashboardLink = 'donor_dashboard.php';
elseif ($userRole === 'volunteer') $dashboardLink = 'volunteer_dashboard.php';
elseif ($userRole === 'admin') $dashboardLink = 'admin/dashboard.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow">
  <div class="container">

    <a class="navbar-brand fw-bold" href="index.php">
      <img src="assets/img/logo.png" alt="HopeBridgeBD" width="90" height="36" loading="lazy">
    </a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="donations.php">Donations</a></li>
        <li class="nav-item"><a class="nav-link" href="volunteers.php">Volunteers</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

        <?php if ($isLoggedIn): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $dashboardLink; ?>">
              <?php echo $userName; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-warning text-warning ms-lg-2 px-3" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link btn btn-warning text-dark fw-semibold ms-lg-2 px-3" href="login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

  </div>
</nav>
<div class="navbar-spacer"></div>
