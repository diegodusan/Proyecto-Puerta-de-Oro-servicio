<?php
// public/save_sale.php
header('Content-Type: application/json');
require '../config/db.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['items'])) {
    echo json_encode(['success' => false, 'error' => 'No items']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Calculate Total & Create Sale
    $total = 0;
    foreach ($data['items'] as $item) {
        $stmt = $pdo->prepare("SELECT sale_price FROM products WHERE id = ?");
        $stmt->execute([$item['id']]);
        $price = $stmt->fetchColumn();
        $total += $price * $item['qty'];
    }

    $stmt = $pdo->prepare("INSERT INTO sales (user_id, cash_register_id, total, payment_method) VALUES (?, ?, ?, 'cash')");
    $stmt->execute([$_SESSION['user_id'] ?? 1, $data['register_id'], $total]);
    $saleId = $pdo->lastInsertId();

    // 2. Insert Items & Updates Stock
    $stmtItem = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($data['items'] as $item) {
        // Fetch current price again to be safe
        $stmtPrice = $pdo->prepare("SELECT sale_price FROM products WHERE id = ?");
        $stmtPrice->execute([$item['id']]);
        $price = $stmtPrice->fetchColumn();
        
        $subtotal = $price * $item['qty'];

        $stmtItem->execute([$saleId, $item['id'], $item['qty'], $price, $subtotal]);
        $stmtStock->execute([$item['qty'], $item['id']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'sale_id' => $saleId]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
