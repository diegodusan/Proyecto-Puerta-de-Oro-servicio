<?php
// public/product_form.php
require '../config/db.php';
require '../includes/header.php';

$id = $_GET['id'] ?? null;
$product = ['name'=>'','code'=>'','category_id'=>'','sale_price'=>'','cost_price'=>'','stock'=>''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

// Categories
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $cat = $_POST['category_id'];
    $price = $_POST['sale_price'];
    $stock = $_POST['stock'];
    
    $stock = $_POST['stock'];
    
    // Image Upload
    $imagePath = $product['image_path'] ?? null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    if ($id) {
        $sql = "UPDATE products SET name=?, category_id=?, sale_price=?, stock=?";
        $params = [$name, $cat, $price, $stock];
        if ($imagePath) {
            $sql .= ", image_path=?";
            $params[] = $imagePath;
        }
        $sql .= " WHERE id=?";
        $params[] = $id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, sale_price, stock, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $cat, $price, $stock, $imagePath]);
    }
    header('Location: products.php');
    exit;
}
?>

<h1><?php echo $id ? 'Editar' : 'Nuevo'; ?> Producto</h1>

<div class="card" style="max-width: 600px;">
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre del Producto</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Categoría</label>
        <select name="category_id">
            <?php foreach($cats as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo $product['category_id'] == $c['id'] ? 'selected' : ''; ?>>
                    <?php echo $c['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Precio Venta</label>
        <input type="number" name="sale_price" value="<?php echo $product['sale_price']; ?>" required>

        <label>Stock</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

        <label>Imagen del Producto</label>
        <?php if(!empty($product['image_path'])): ?>
            <div style="margin: 10px 0;">
                <img src="<?php echo $product['image_path']; ?>" style="height: 100px; border-radius: 8px;">
            </div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*" style="padding: 10px; background: #333;">

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">GUARDAR</button>
        <a href="products.php" class="btn btn-outline" style="margin-top: 1rem;">Cancelar</a>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
