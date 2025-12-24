<?php
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "Checking 'admin123': " . (password_verify('admin123', $hash) ? 'MATCH' : 'NO MATCH') . "\n";
echo "Checking 'password': " . (password_verify('password', $hash) ? 'MATCH' : 'NO MATCH') . "\n";
?>
