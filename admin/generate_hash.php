<?php
// Replace 'Admin@123' with your desired admin password
$password = 'Admin@123'; 

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Your hashed password is: " . $hash;
?>
