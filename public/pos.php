<?php
// public/pos.php
require '../config/db.php';
require '../includes/header.php';

// Check if open register
$stmt = $pdo->prepare("SELECT id FROM cash_register WHERE status = 'open' LIMIT 1");
$stmt->execute();
$register = $stmt->fetch();
if (!$register) {
    echo "<div class='card' style='text-align: center; color: var(--danger);'><h2>Caja Cerrada</h2><p>Debe abrir caja antes de vender.</p><br><a href='caja.php' class='btn btn-primary'>Ir a Caja</a></div>";
    require '../includes/footer.php';
    exit;
}

// Get Categories and Products
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
$products = $pdo->query("SELECT * FROM products WHERE stock > 0")->fetchAll();
?>

<div style="display: flex; gap: 1rem; height: calc(100vh - 100px);">
    <!-- Product Grid -->
    <div style="flex: 3; overflow-y: auto; padding-right: 0.5rem;">
        <!-- Category Filter -->
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
            <button class="btn btn-outline cat-btn active" onclick="filterCat('all', this)">Todo</button>
            <?php foreach($cats as $c): ?>
                <button class="btn btn-outline cat-btn" onclick="filterCat(<?php echo $c['id']; ?>, this)"><?php echo $c['name']; ?></button>
            <?php endforeach; ?>
        </div>

        <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem;">
            <?php foreach($products as $p): ?>
            <div class="card product-card" 
                 data-cat="<?php echo $p['category_id']; ?>" 
                 onclick="addToCart(<?php echo htmlspecialchars(json_encode($p)); ?>)"
                 style="cursor: pointer; text-align: center; padding: 1rem; transition: transform 0.1s;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🍸</div>
                <h4 style="font-size: 0.9rem; height: 40px; overflow: hidden;"><?php echo htmlspecialchars($p['name']); ?></h4>
                <p style="color: var(--gold-primary); font-weight: bold;">$<?php echo number_format($p['sale_price'], 0); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Cart -->
    <div class="card" style="flex: 1.5; display: flex; flex-direction: column;">
        <h3>Nueva Venta</h3>
        <div id="cart-items" style="flex: 1; overflow-y: auto; margin: 1rem 0; border-top: 1px solid #333; border-bottom: 1px solid #333;">
            <!-- Items go here -->
            <p style="text-align: center; color: #666; margin-top: 2rem;">Carrito vacío</p>
        </div>
        
        <div style="margin-top: auto;">
            <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; margin-bottom: 1rem;">
                <span>Total:</span>
                <span id="cart-total" style="color: var(--gold-primary);">$0</span>
            </div>
            <button class="btn btn-primary" style="width: 100%;" onclick="processSale()">COBRAR</button>
        </div>
    </div>
</div>

<script src="js/pos.js"></script>
<script>
    // Initialize
    const REGISTER_ID = <?php echo $register['id']; ?>;
    
    function processSaleWrapper() {
        processSale(REGISTER_ID);
    }
</script>

<!-- Button Override for Process -->
<style>
    .btn-mini { background: #333; border: 1px solid #555; color: white; border-radius: 4px; cursor: pointer; }
    .btn-icon { background: none; border: none; font-size: 1.2rem; cursor: pointer; }
    .text-gold { color: var(--gold-primary); }
    .text-danger { color: var(--danger); }
</style>
<button class="btn btn-primary" style="width: 100%;" onclick="processSaleWrapper()">COBRAR</button>


<?php require '../includes/footer.php'; ?>
