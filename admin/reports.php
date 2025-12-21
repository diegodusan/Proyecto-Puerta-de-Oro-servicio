<?php
// admin/reports.php
require '../config/db.php';
require 'header_admin.php';

$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-d');

// Sales by Category
$catSql = "SELECT c.name, SUM(si.subtotal) as total 
           FROM sale_items si 
           JOIN products p ON si.product_id = p.id 
           JOIN categories c ON p.category_id = c.id 
           JOIN sales s ON si.sale_id = s.id 
           WHERE DATE(s.created_at) BETWEEN ? AND ? 
           GROUP BY c.name";
$stmt = $pdo->prepare($catSql);
$stmt->execute([$start, $end]);
$byCat = $stmt->fetchAll();

// Top Products
$prodSql = "SELECT p.name, SUM(si.quantity) as qty, SUM(si.subtotal) as total
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            JOIN sales s ON si.sale_id = s.id
            WHERE DATE(s.created_at) BETWEEN ? AND ?
            GROUP BY p.name
            ORDER BY total DESC LIMIT 5";
$stmt2 = $pdo->prepare($prodSql);
$stmt2->execute([$start, $end]);
$topProds = $stmt2->fetchAll();
?>

<h1>Reportes Avanzados</h1>

<form class="card" style="display: flex; gap: 1rem; align-items: flex-end;">
    <div>
        <label>Desde</label>
        <input type="date" name="start" value="<?php echo $start; ?>">
    </div>
    <div>
        <label>Hasta</label>
        <input type="date" name="end" value="<?php echo $end; ?>">
    </div>
    <button class="btn btn-primary">Filtrar</button>
</form>

<div style="display: flex; gap: 2rem; margin-top: 2rem; flex-wrap: wrap;">
    <div class="card" style="flex: 1;">
        <h3>Ventas por Categoría</h3>
        <table>
            <?php foreach($byCat as $row): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td style="text-align: right;">$<?php echo number_format($row['total'], 0); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card" style="flex: 1;">
        <h3>Top 5 Productos</h3>
        <table>
            <thead><tr><th>Producto</th><th>Cant.</th><th>Total</th></tr></thead>
            <?php foreach($topProds as $row): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['qty']; ?></td>
                <td style="text-align: right;">$<?php echo number_format($row['total'], 0); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
