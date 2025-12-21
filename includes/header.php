<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/public'; // Adjust if needed based on local server
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puerta de Oro GastroBar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
    <nav class="sidebar">
        <div class="logo-area">
            PUERTA DE ORO
        </div>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="pos.php" class="nav-link" style="color: var(--neon-accent);">Punto de Venta</a>
            <a href="sales.php" class="nav-link">Historial Ventas</a>
            <a href="products.php" class="nav-link">Inventario</a>
            <a href="caja.php" class="nav-link">Caja</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="users.php" class="nav-link">Usuarios</a>
            <?php endif; ?>
            <a href="logout.php" class="nav-link" style="margin-top: 2rem; color: var(--danger);">Cerrar Sesión</a>
        </div>
    </nav>
    <main class="main-content">
<?php endif; ?>
