/* public/js/pos.js */
let cart = [];

// Add item to cart
function addToCart(product) {
    const existing = cart.find(i => i.id === product.id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    renderCart();
    playSound('beep'); // Optional: defined later or ignored
}

// Remove item
function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

// Render Cart UI
function renderCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');

    if (cart.length === 0) {
        container.innerHTML = "<p class='text-muted text-center mt-4'>Carrito vacío</p>";
        totalEl.innerText = "$0";
        return;
    }

    let html = '<table class="cart-table" style="width: 100%; font-size: 0.9rem;">';
    let total = 0;

    cart.forEach(item => {
        const subtotal = item.sale_price * item.qty;
        total += subtotal;
        html += `
            <tr class="cart-item">
                <td style="padding: 0.5rem;">
                    <strong>${item.name}</strong><br>
                    <small class="text-gold">$${item.sale_price}</small>
                </td>
                <td style="padding: 0.5rem;">
                    <button class="btn-mini" onclick="adjustQty(${item.id}, -1)">-</button>
                    <span style="padding: 0 5px">${item.qty}</span>
                    <button class="btn-mini" onclick="adjustQty(${item.id}, 1)">+</button>
                </td>
                <td style="padding: 0.5rem;">$${subtotal.toLocaleString()}</td>
                <td style="padding: 0.5rem;">
                    <button onclick="removeFromCart(${item.id})" class="text-danger btn-icon">&times;</button>
                </td>
            </tr>
        `;
    });
    html += '</table>';
    container.innerHTML = html;
    totalEl.innerText = "$" + total.toLocaleString();
}

// Adjust Quantity
function adjustQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    item.qty += change;
    if (item.qty <= 0) {
        removeFromCart(id);
    } else {
        renderCart();
    }
}

// Filter Categories
function filterCat(id, btn) {
    // Update active button state
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const cards = document.querySelectorAll('.product-card');
    cards.forEach(el => {
        if (id === 'all' || el.dataset.cat == id) {
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    });
}

// Process Sale
function processSale(registerId) {
    if (cart.length === 0) return alert("Carrito vacío");

    const totalText = document.getElementById('cart-total').innerText;
    if (!confirm("Confirmar venta por " + totalText + "?")) return;

    const data = {
        items: cart.map(i => ({ id: i.id, qty: i.qty })),
        register_id: registerId
    };

    fetch('save_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (confirm("Venta registrada! ¿Imprimir ticket?")) {
                    window.open('ticket.php?id=' + res.sale_id, '_blank');
                }
                cart = [];
                renderCart();
            } else {
                alert("Error: " + res.error);
            }
        })
        .catch(err => alert("Error de conexión: " + err));
}
