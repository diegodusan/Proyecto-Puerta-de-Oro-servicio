<?php
// public/ticket.php
require '../config/db.php';
session_start();

$id = $_GET['id'] ?? null;
if (!$id) die("Venta no especificada");

$stmt = $pdo->prepare("SELECT s.*, u.username FROM sales s LEFT JOIN users u ON s.user_id = u.id WHERE s.id = ?");
$stmt->execute([$id]);
$sale = $stmt->fetch();

if (!$sale) die("Venta no encontrada");

$stmtItems = $pdo->prepare("SELECT si.*, p.name FROM sale_items si LEFT JOIN products p ON si.product_id = p.id WHERE si.sale_id = ?");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?php echo $sale['id']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #fff;
            color: #000;
            width: 300px; /* Width for thermal printer */
            margin: 0;
            padding: 1rem;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h2 { margin: 0; font-size: 1.2rem; }
        .info { font-size: 0.8rem; margin-bottom: 10px; }
        .items-table {
            width: 100%;
            font-size: 0.8rem;
            border-collapse: collapse;
        }
        .items-table th { text-align: left; border-bottom: 1px solid #000; }
        .total-section {
            text-align: right;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.7rem;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>PUERTA DE ORO</h2>
        <p>GastroBar</p>
        <p>Bochalema, N. de S.</p>
    </div>
    
    <div class="info">
        Ticket #: <?php echo $sale['id']; ?><br>
        Fecha: <?php echo $sale['created_at']; ?><br>
        Atendido por: <?php echo $sale['username']; ?>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Prod.</th>
                <th>Cant.</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['subtotal'], 0); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        TOTAL: $<?php echo number_format($sale['total'], 0); ?>
    </div>

    <div class="footer">
        <p>¡Gracias por su visita!</p>
        <p>Software by Gemini</p>
    </div>
</body>
</html>
