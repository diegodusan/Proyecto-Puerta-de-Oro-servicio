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
            <tr>
                <td>
                    <?php if($p['image_path']): ?>
                        <img src="<?php echo $p['image_path']; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <span style="font-size: 1.5rem;">🍸</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['category_name'] ?? 'Sin Categ.'); ?></td>
                <td style="color: var(--success); font-weight: bold;">$<?php echo number_format($p['sale_price'], 0); ?></td>
                <td>
                    <?php 
                    $color = $p['stock'] < 10 ? 'var(--danger)' : 'white';
                    echo "<span style='color: $color'>{$p['stock']}</span>"; 
                    ?>
                </td>
                <td>
                    <a href="product_form.php?id=<?php echo $p['id']; ?>" style="color: var(--neon-accent);">Editar</a>
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
