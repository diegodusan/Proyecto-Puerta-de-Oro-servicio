<?php
// public/import_exec.php
require '../config/db.php';

$file = '../Precios 201225.xlsx';
$pythonScript = 'xlsx_reader.py';

if (!file_exists($file)) {
    die("Archivo Excel no encontrado en: $file");
}

echo "Leyendo archivo Excel...<br>";

$command = "python3 " . escapeshellarg($pythonScript) . " " . escapeshellarg($file);
$output = shell_exec($command);
$json = json_decode($output, true);

if (isset($json['error'])) {
    die("Error leyendo Excel: " . $json['error']);
}

$rows = $json['data'];

// Assumption: Row 1 is headers.
// We expect headers roughly: Producto, Categoria, Precio
// But for now, let's just dump what we found so the user can see, then we map it.
// We will try to auto-map based on position if simple using index.

echo "<h3>Datos encontrados (" . count($rows) . " filas)</h3>";

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO products (name, sale_price, stock, category_id) VALUES (?, ?, ?, ?)");
    
    // Create Default Category
    $stmtCat = $pdo->query("SELECT id FROM categories WHERE name='Licores'");
    $defaultCat = $stmtCat->fetchColumn();
    if (!$defaultCat) $defaultCat = 1;

    $count = 0;
    foreach ($rows as $i => $row) {
        if ($i < 1) continue; // Skip header
        if (empty($row[0])) continue;

        $name = $row[0] ?? 'Producto Sin Nombre';
        $price = $row[1] ?? 0; // Assuming Col B is Price
        
        // Cleanup price
        $price = str_replace(['$', ',', '.'], '', $price); 
        // If it was 12.000 it becomes 12000. If 12,000 becomes 12000.
        // Needs care.

        $stmt->execute([$name, (float)$price, 100, $defaultCat]);
        $count++;
    }
    $pdo->commit();
    echo "Importados $count productos exitosamente.<br>";
    echo "<a href='products.php'>Ir a Productos</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error importando BD: " . $e->getMessage();
}
?>
