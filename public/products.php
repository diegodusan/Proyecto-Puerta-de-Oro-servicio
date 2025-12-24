<?php
// public/products.php
require '../config/db.php';
require '../includes/header.php';

// Pagination
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>Inventario de Productos</h1>
    <div>
        <a href="product_form.php" class="btn btn-primary">Nuevo Producto</a>
        <a href="import_exec.php" class="btn btn-outline" onclick="return confirm('¿Importar desde Excel? Esto añadirá productos.')">Importar Excel</a>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Img</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio Venta</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr style="cursor: pointer;">
                <td>
                    <?php if($p['image_path']): ?>
                        <img src="<?php echo $p['image_path']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                    <?php else: ?>
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🍸</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($p['name']); ?></div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);"><?php echo htmlspecialchars($p['code'] ?? ''); ?></div>
                </td>
                <td><span class="badge" style="background: rgba(0, 158, 115, 0.1); color: var(--brand-teal);"><?php echo htmlspecialchars($p['category_name'] ?? 'Sin Categ.'); ?></span></td>
                <td style="color: var(--brand-lime); font-weight: bold; font-size: 1.1rem;">$<?php echo number_format($p['sale_price'], 0); ?></td>
                <td>
                    <?php 
                    $isLow = $p['stock'] < 10;
                    $class = $isLow ? 'badge-danger' : 'badge-success';
                    $icon = $isLow ? '⚠️' : '✅';
                    echo "<span class='badge $class'>$icon {$p['stock']}</span>"; 
                    ?>
                </td>
                <td>
                    <a href="product_form.php?id=<?php echo $p['id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Editar</a>
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
