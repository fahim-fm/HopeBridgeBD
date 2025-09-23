<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];
$area = $_POST['area'];
$status = isset($_POST['status']) ? $_POST['status'] : "Available";

$image = "";
if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
    $targetDir = "uploads/";
    if(!is_dir($targetDir)) mkdir($targetDir);
    $fileName = time()."_".basename($_FILES['image']['name']);
    $targetFile = $targetDir.$fileName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    $image = $fileName;
}

if(!empty($_POST['id'])){ 
    // Update
    $id = $_POST['id'];
    if($image){
        $sql = "UPDATE donations SET title='$title', category='$category', description='$description', area='$area', status='$status', image='$image' WHERE id='$id' AND donor_id='$user_id'";
    } else {
        $sql = "UPDATE donations SET title='$title', category='$category', description='$description', area='$area', status='$status' WHERE id='$id' AND donor_id='$user_id'";
    }
    mysqli_query($conn, $sql);
} else {
    // Insert
    $sql = "INSERT INTO donations(donor_id, title, category, description, area, image, status) 
            VALUES('$user_id','$title','$category','$description','$area','$image','$status')";
    mysqli_query($conn, $sql);
}

header("Location: donor_dashboard.php");
exit;
?>
