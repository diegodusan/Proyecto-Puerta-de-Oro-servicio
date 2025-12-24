<?php
// admin/index.php
require '../config/db.php';
require 'header_admin.php';

// Stats
$totalSales = $pdo->query("SELECT SUM(total) FROM sales")->fetchColumn() ?: 0;
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 5")->fetchColumn();
?>

<h1>Panel de Administración</h1>

<div class="grid-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
    <div class="card">
        <h3>Ventas Totales</h3>
        <p style="font-size: 1.5rem; color: var(--brand-lime);">$<?php echo number_format($totalSales, 0); ?></p>
    </div>
    <div class="card">
        <h3>Usuarios</h3>
        <p style="font-size: 1.5rem; color: white;"><?php echo $totalUsers; ?></p>
    </div>
    <div class="card">
        <h3>Productos</h3>
        <p style="font-size: 1.5rem; color: white;"><?php echo $totalProducts; ?></p>
    </div>
    <div class="card" style="border-color: var(--danger);">
        <h3>Stock Bajo</h3>
        <p style="font-size: 1.5rem; color: var(--danger);"><?php echo $lowStock; ?></p>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h3>Acciones Rápidas</h3>
    <a href="users.php" class="btn btn-primary">Crear Usuario</a>
    <a href="reports.php" class="btn btn-outline">Ver Reporte Mensual</a>
</div>

<?php require '../includes/footer.php'; ?>
