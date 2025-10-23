<?php
session_start();
include '../db.php';

// Access control: only logged-in admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// Get volunteer id
if(!isset($_GET['id'])){
    header("Location: manage_volunteers.php");
    exit;
}

$id = $_GET['id'];

// Fetch volunteer info
$res = mysqli_query($conn, "
    SELECT v.*, u.name, u.email, u.phone, u.area
    FROM volunteers v
    JOIN users u ON v.user_id = u.id
    WHERE v.id='$id'
");
$volunteer = mysqli_fetch_assoc($res);

if(!$volunteer){
    header("Location: manage_volunteers.php");
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $area = $_POST['area'];
    $availability = $_POST['availability'];

    // Update users table
    mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone', area='$area' WHERE id='{$volunteer['user_id']}'");

    // Handle image upload
    $image = $volunteer['image']; // keep old if no new
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $targetDir = "../uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir);
        $fileName = time()."_".basename($_FILES['image']['name']);
        $targetFile = $targetDir.$fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $image = $fileName;
    }

    // Update volunteers table
    mysqli_query($conn, "UPDATE volunteers SET availability='$availability', image='$image' WHERE id='$id'");

    header("Location: manage_volunteers.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Volunteer - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="icon" sizes="32x32" type="image/png" href="../favicon.png">

</head>
<body>

<div class="container py-5">
  <h3>Edit Volunteer</h3>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($volunteer['name']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($volunteer['phone']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Area</label>
      <input type="text" name="area" class="form-control" value="<?php echo htmlspecialchars($volunteer['area']); ?>" required>
    </div>
    <div class="mb-3">
      <label>Availability</label>
      <select name="availability" class="form-control" required>
        <option value="Active" <?php if($volunteer['availability']=='Active') echo 'selected'; ?>>Active</option>
        <option value="Not Available" <?php if($volunteer['availability']=='Not Available') echo 'selected'; ?>>Not Available</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Image (optional)</label>
      <input type="file" name="image" class="form-control">
      <?php if($volunteer['image']){ ?>
        <img src="../uploads/<?php echo $volunteer['image']; ?>" width="80" class="mt-2" alt="Volunteer">
      <?php } ?>
    </div>
    <button type="submit" class="btn btn-success">Update Volunteer</button>
    <a href="manage_volunteers.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
