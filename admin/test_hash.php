<?php
$hash = '$2y$10$eKj3kqG/JcXjHgV9smnH6uMGW3kg1zR0uDK/8p6Bub.xOjC6l3K7C'; // From DB
if(password_verify('Admin@123', $hash)){
    echo "Password is correct!";
} else {
    echo "Password does NOT match!";
}
?>
