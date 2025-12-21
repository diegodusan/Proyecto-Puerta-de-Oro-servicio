<?php
// config/db.php
$host = 'code_utilities_proyecto_puerta_de_oro';
$dbname = 'code_utilities';
$username = 'mysql';
$password = '4cecc675969ffd40bf09';
$port = '3306';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Attempting to catch connection error
    die("Connection failed: " . $e->getMessage());
}
?>
