<?php
// public/install.php
require '../config/db.php';

echo "<h1>System Installation</h1>";
echo "Setting up database...<br>";

try {
    $sql = file_get_contents('../config/schema.sql');
    $pdo->exec($sql);
    echo "<p style='color: green;'>Database schema imported successfully!</p>";
    echo "<p>Default admin user created: <strong>admin</strong> / <strong>admin123</strong></p>"; 
    echo "<p><a href='index.php'>Go to Login</a></p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error importing schema: " . $e->getMessage() . "</p>";
}
?>
