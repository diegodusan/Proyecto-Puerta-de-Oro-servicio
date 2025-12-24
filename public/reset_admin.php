<?php
// public/reset_admin.php
require '../config/db.php';

// Hash for 'admin123'
$new_hash = password_hash('admin123', PASSWORD_DEFAULT);

try {
    // Update admin user
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'admin'");
    $stmt->execute([$new_hash]);
    
    // Ensure admin exists if update affected 0 rows
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES ('admin', ?, 'admin')");
        $stmt->execute([$new_hash]);
        echo "Usuario 'admin' creado con contraseña 'admin123'.";
    } else {
        echo "Contraseña de 'admin' restablecida a 'admin123'.";
    }
    
    echo "<br><a href='index.php'>Ir al Login</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
