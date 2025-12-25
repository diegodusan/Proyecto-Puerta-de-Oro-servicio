<?php
// public/dashboard.php
require '../includes/header.php';
require '../config/db.php';

// 1. Get Open Cash Register
$stmt = $pdo->prepare("SELECT * FROM cash_register WHERE status = 'open' ORDER BY id DESC LIMIT 1");
$stmt->execute();
$register = $stmt->fetch();

$opening_balance = $register ? $register['opening_balance'] : 0;
$cash_id = $register ? $register['id'] : null;

// 2. Calculate Sales for Today (or current register)
$salesToday = 0;
$salesCount = 0;

if ($cash_id) {
    // If register is open, sum sales for this register since it opened
    $stmt = $pdo->prepare("SELECT SUM(total) as total, COUNT(*) as count FROM sales WHERE cash_register_id = ?");
    $stmt->execute([$cash_id]);
    $res = $stmt->fetch();
    $salesToday = $res['total'] ?? 0;
    $salesCount = $res['count'] ?? 0;
} else {
    // Fallback: Sales of the current day
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT SUM(total) as total, COUNT(*) as count FROM sales WHERE DATE(created_at) = ?");
    $stmt->execute([$today]);
    $res = $stmt->fetch();
    $salesToday = $res['total'] ?? 0;
    $salesCount = $res['count'] ?? 0;
}

$totalInBox = $opening_balance + $salesToday;

// 3. Inventory Count
$prodCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
?>

<div class="dashboard-header animate__animated animate__fadeInDown">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <p style="color: var(--text-secondary);">Panel de Control - GastroBar</p>
</div>

<div class="grid-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <!-- Widget Caja -->
    <div class="card glass-panel" style="animation-delay: 0.1s;">
        <h3>💰 En Caja</h3>
        <?php if($register): ?>
            <p style="font-size: 2.5rem; color: var(--success); font-weight: 800;">$<?php echo number_format($totalInBox, 0); ?></p>
            <p style="color: var(--text-secondary);">Base: $<?php echo number_format($opening_balance, 0); ?> + Ventas: $<?php echo number_format($salesToday, 0); ?></p>
        <?php else: ?>
            <p style="font-size: 2rem; color: var(--danger); font-weight: 800;">CERRADA</p>
            <p style="color: var(--text-secondary);">Debe abrir caja para facturar</p>
        <?php endif; ?>
        <a href="caja.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">Administrar Caja</a>
    </div>

    <!-- Widget Ventas -->
    <div class="card glass-panel" style="animation-delay: 0.2s;">
        <h3>📈 Ventas Hoy</h3>
        <p style="font-size: 2.5rem; color: var(--brand-lime); font-weight: 800;"><?php echo $salesCount; ?></p>
        <p style="color: var(--text-secondary);">Total Facturado: $<?php echo number_format($salesToday, 0); ?></p>
        <a href="pos.php" class="btn btn-primary primary-glow" style="margin-top: 1rem; display: inline-block;">Nueva Venta</a>
    </div>
    
    <!-- Widget Productos -->
    <div class="card glass-panel" style="animation-delay: 0.3s;">
        <h3>📦 Inventario</h3>
        <p style="font-size: 2.5rem; color:white; font-weight: 800;"><?php echo $prodCount; ?></p>
        <p style="color: var(--text-secondary);">Productos registrados</p>
        <a href="products.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">Gestionar</a>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
