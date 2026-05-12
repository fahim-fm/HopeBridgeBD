<?php
/**
 * Admin Password Hash Generator
 * Run this ONCE in browser to get a bcrypt hash, then DELETE this file.
 * Usage: http://localhost/hopebd/admin/generate_hash.php
 */

// 1. Set your desired admin password below
$password = 'Admin@123';

// 2. Generate the bcrypt hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo '<style>body{font-family:monospace;padding:30px;background:#f5f5f5;}</style>';
echo '<h3>Password Hash Generator</h3>';
echo '<p><strong>Password:</strong> ' . htmlspecialchars($password) . '</p>';
echo '<p><strong>Hashed:</strong><br><code style="word-break:break-all;">' . $hash . '</code></p>';
echo '<hr><p style="color:red;"><strong>⚠ Delete this file immediately after use!</strong></p>';

// 3. Verify it works
echo '<p><strong>Verification:</strong> ';
echo password_verify($password, $hash) ? '✅ Hash is valid.' : '❌ Hash mismatch!';
echo '</p>';
?>
