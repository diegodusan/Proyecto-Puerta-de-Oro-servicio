<?php
// public/caja.php
require '../config/db.php';
require '../includes/header.php';

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Check if open register exists
$stmt = $pdo->prepare("SELECT * FROM cash_register WHERE status = 'open' ORDER BY id DESC LIMIT 1");
$stmt->execute();
$register = $stmt->fetch();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['open_caja'])) {
        $amount = $_POST['amount'];
        $stmt = $pdo->prepare("INSERT INTO cash_register (user_id, opening_balance, status) VALUES (?, ?, 'open')");
        $stmt->execute([$user_id, $amount]);
        header("Refresh:0");
    } elseif (isset($_POST['close_caja'])) {
        $id = $_POST['register_id'];
        // Calculate total sales for this register (TODO: Real calculation)
        $totalSales = 0; // Placeholder until POS is linked
        $finalBalance = $register['opening_balance'] + $totalSales;
        
        $stmt = $pdo->prepare("UPDATE cash_register SET closing_balance = ?, status = 'closed', closing_time = NOW() WHERE id = ?");
        $stmt->execute([$finalBalance, $id]);
        header("Refresh:0");
    }
}
?>

<h1>Gestión de Caja</h1>

<?php if (!$register): ?>
    <div class="card" style="max-width: 500px; margin: 2rem auto; text-align: center;">
        <h2 style="color: var(--danger);">Caja Cerrada</h2>
        <p>Debe abrir la caja para comenzar a vender.</p>
        <form method="POST" style="margin-top: 2rem;">
            <label>Monto Inicial (Base)</label>
            <input type="number" name="amount" value="0" min="0" required>
            <button type="submit" name="open_caja" class="btn btn-primary" style="width: 100%;">ABRIR CAJA</button>
        </form>
    </div>
<?php else: ?>
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h3>Estado: <span style="color: var(--success);">ABIERTA</span></h3>
                <p>Abierta por: Usuario #<?php echo $register['user_id']; ?></p>
                <p>Hora: <?php echo $register['opening_time']; ?></p>
            </div>
            <div style="text-align: right;">
                <h3>Base Inicial</h3>
                <p style="font-size: 1.5rem;">$<?php echo number_format($register['opening_balance'], 0); ?></p>
            </div>
        </div>
        
        <hr style="border-color: #333; margin: 1rem 0;">
        
        <div style="text-align: center; margin: 2rem 0;">
            <h2>Ventas del Turno</h2>
            <p style="font-size: 3rem; color: var(--brand-lime);">$0.00</p>
            <small>(Calculado automáticamente desde Ventas)</small>
        </div>

        <form method="POST">
            <input type="hidden" name="register_id" value="<?php echo $register['id']; ?>">
            <button type="submit" name="close_caja" class="btn btn-outline" style="width: 100%; border-color: var(--danger); color: var(--danger);">CERRAR CAJA Y GENERAR REPORTE</button>
        </form>
    </div>
<?php endif; ?>

<?php require '../includes/footer.php'; ?>
