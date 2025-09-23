<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM donations WHERE id='$id' AND donor_id='$user_id'");

header("Location: donor_dashboard.php");
exit;
?>
