<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$title = $description = $category = $status = $area = "";
$edit = false;

if(isset($_GET['id'])){
    $edit = true;
    $id = $_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM donations WHERE id='$id' AND donor_id='$user_id'");
    if($row = mysqli_fetch_assoc($res)){
        $title = $row['title'];
        $description = $row['description'];
        $category = $row['category'];
        $status = $row['status'];
        $area = $row['area'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $edit ? "Edit" : "Add"; ?> Donation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3><?php echo $edit ? "Edit" : "Add"; ?> Donation</h3>
  <form method="post" action="donation_save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $edit ? $id : ""; ?>">
    <div class="mb-3">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
    </div>
    <div class="mb-3">
      <label>Category</label>
      <select name="category" class="form-control" required>
        <option value="">Select</option>
        <option value="Cooked Food" <?php if($category=="Cooked Food") echo "selected"; ?>>Cooked Food</option>
        <option value="Dry Food" <?php if($category=="Dry Food") echo "selected"; ?>>Dry Food</option>
        <option value="Clothes" <?php if($category=="Clothes") echo "selected"; ?>>Clothes</option>
        <option value="Winter Items" <?php if($category=="Winter Items") echo "selected"; ?>>Winter Items</option>
        <option value="Stationery/Books" <?php if($category=="Stationery/Books") echo "selected"; ?>>Stationery/Books</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" required><?php echo $description; ?></textarea>
    </div>
    <div class="mb-3">
      <label>Area</label>
      <input type="text" name="area" class="form-control" value="<?php echo $area; ?>" placeholder="Feni/Cumilla" required>
    </div>
    <div class="mb-3">
      <label>Image</label>
      <input type="file" name="image" class="form-control" <?php if(!$edit) echo "required"; ?>>
    </div>
    <?php if($edit){ ?>
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="Available" <?php if($status=="Available") echo "selected"; ?>>Available</option>
        <option value="Claimed" <?php if($status=="Claimed") echo "selected"; ?>>Claimed</option>
        <option value="Delivered" <?php if($status=="Delivered") echo "selected"; ?>>Delivered</option>
      </select>
    </div>
    <?php } ?>
    <button type="submit" class="btn btn-success"><?php echo $edit ? "Update" : "Add"; ?> Donation</button>
    <a href="donor_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
