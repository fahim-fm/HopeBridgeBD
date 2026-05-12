<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id     = (int)$_SESSION['user_id'];
$title       = trim($_POST['title']       ?? '');
$category    = trim($_POST['category']    ?? '');
$description = trim($_POST['description'] ?? '');
$area        = trim($_POST['area']        ?? '');
$status      = trim($_POST['status']      ?? 'Available');

// Whitelist status
$allowedStatuses = ['Available', 'Claimed', 'Delivered'];
if (!in_array($status, $allowedStatuses)) $status = 'Available';

// Handle image upload
$image       = '';
$allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$maxSize     = 5 * 1024 * 1024; // 5 MB

if (!empty($_FILES['image']['name'])) {
    $fileTmp  = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    $fileMime = mime_content_type($fileTmp);

    if (in_array($fileMime, $allowedMime) && $fileSize <= $maxSize) {
        $targetDir = __DIR__ . '/uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        move_uploaded_file($fileTmp, $targetDir . $fileName);
        $image = $fileName;
    }
}

$edit_id = (int)($_POST['id'] ?? 0);

if ($edit_id > 0) {
    // UPDATE
    if ($image) {
        $stmt = mysqli_prepare($conn,
            "UPDATE donations SET title=?,category=?,description=?,area=?,status=?,image=?
             WHERE id=? AND donor_id=?");
        mysqli_stmt_bind_param($stmt, 'ssssssii', $title, $category, $description, $area, $status, $image, $edit_id, $user_id);
    } else {
        $stmt = mysqli_prepare($conn,
            "UPDATE donations SET title=?,category=?,description=?,area=?,status=?
             WHERE id=? AND donor_id=?");
        mysqli_stmt_bind_param($stmt, 'sssssii', $title, $category, $description, $area, $status, $edit_id, $user_id);
    }
} else {
    // INSERT
    $stmt = mysqli_prepare($conn,
        "INSERT INTO donations (donor_id,title,category,description,area,image,status)
         VALUES (?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'issssss', $user_id, $title, $category, $description, $area, $image, $status);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: donor_dashboard.php");
exit;
