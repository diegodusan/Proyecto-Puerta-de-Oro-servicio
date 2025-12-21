<?php
// public/dashboard.php
require '../includes/header.php';
?>

<div class="dashboard-header">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <p style="color: var(--text-secondary);">Panel de Control - GastroBar</p>
</div>

<div class="grid-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <!-- Widget Caja -->
    <div class="card">
        <h3>Caja</h3>
        <p style="font-size: 2rem; color: var(--success); font-weight: bold;">$0.00</p>
        <p style="color: var(--text-secondary);">En caja hoy</p>
        <a href="caja.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">Ver Caja</a>
    </div>

    <!-- Widget Ventas -->
    <div class="card">
        <h3>Ventas Hoy</h3>
        <p style="font-size: 2rem; color: var(--gold-primary); font-weight: bold;">0</p>
        <p style="color: var(--text-secondary);">Tickets generados</p>
        <a href="pos.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Nueva Venta</a>
    </div>
    
    <!-- Widget Productos -->
    <div class="card">
        <h3>Inventario</h3>
        <p style="font-size: 2rem; color:white; font-weight: bold;">0</p>
        <p style="color: var(--text-secondary);">Productos registrados</p>
        <a href="products.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">Gestionar</a>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
