<?php
include 'db.php';

// Initialize variables
$title = $description = $category = $status = $area = "";
$edit = false;

if(isset($_GET['id'])){
    $edit = true;
    $id = $_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM donations WHERE id='$id'");
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
   <link rel="icon" sizes="32x32" type="image/png" href="favicon.png">

</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-success text-white text-center fw-bold">
          <?php echo $edit ? "Edit Donation" : "Add Donation"; ?>
        </div>
        <div class="card-body p-4">
          <form method="post" action="donation_save.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $edit ? $id : ""; ?>">

            <div class="mb-3">
              <label class="form-label fw-semibold">Title</label>
              <input type="text" name="title" class="form-control" 
                     value="<?php echo $title; ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Category</label>
              <select name="category" class="form-select" required>
                <option value="">Select</option>
                <option value="Cooked Food" <?php if($category=="Cooked Food") echo "selected"; ?>>Cooked Food</option>
                <option value="Dry Food" <?php if($category=="Dry Food") echo "selected"; ?>>Dry Food</option>
                <option value="Clothes" <?php if($category=="Clothes") echo "selected"; ?>>Clothes</option>
                <option value="Winter Items" <?php if($category=="Winter Items") echo "selected"; ?>>Winter Items</option>
                <option value="Stationery/Books" <?php if($category=="Stationery/Books") echo "selected"; ?>>Stationery/Books</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="3" required><?php echo $description; ?></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Area</label>
              <input type="text" name="area" class="form-control" 
                     value="<?php echo $area; ?>" placeholder="Feni / Cumilla" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Image</label>
              <input type="file" name="image" class="form-control" <?php if(!$edit) echo "required"; ?>>
            </div>

            <?php if($edit){ ?>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="Available" <?php if($status=="Available") echo "selected"; ?>>Available</option>
                <option value="Claimed" <?php if($status=="Claimed") echo "selected"; ?>>Claimed</option>
                <option value="Delivered" <?php if($status=="Delivered") echo "selected"; ?>>Delivered</option>
              </select>
            </div>
            <?php } ?>

            <div class="d-flex justify-content-between mt-4">
              <button type="submit" class="btn btn-success px-4">
                <?php echo $edit ? "Update Donation" : "Add Donation"; ?>
              </button>
              <a href="index.php" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <small class="text-muted">
            Want to track your donations? <a href="login.php" class="text-decoration-none fw-semibold">Donate with Login</a>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
