<?php
// public/sales.php
require '../config/db.php';
require '../includes/header.php';

// Pagination
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT s.*, u.username, cr.id as caja_id 
                       FROM sales s 
                       LEFT JOIN users u ON s.user_id = u.id 
                       LEFT JOIN cash_register cr ON s.cash_register_id = cr.id 
                       ORDER BY s.created_at DESC 
                       LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$sales = $stmt->fetchAll();
?>

<h1>Historial de Ventas</h1>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Turno (Caja)</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $s): ?>
            <tr>
                <td>#<?php echo $s['id']; ?></td>
                <td><?php echo $s['created_at']; ?></td>
                <td><?php echo htmlspecialchars($s['username'] ?? 'N/A'); ?></td>
                <td>#<?php echo $s['caja_id']; ?></td>
                <td style="color: var(--brand-lime); font-weight: bold;">$<?php echo number_format($s['total'], 0); ?></td>
                <td>
                    <a href="ticket.php?id=<?php echo $s['id']; ?>" target="_blank" class="btn btn-outline" style="font-size: 0.8rem;">Ver Ticket</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="pagination" style="margin-top: 1rem; text-align: center;">
        <?php if($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>" class="btn btn-outline">Anterior</a>
        <?php endif; ?>
        <a href="?page=<?php echo $page+1; ?>" class="btn btn-outline">Siguiente</a>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
