<?php include 'includes/header.php'; ?>

<div class="container my-4">
    <h2>Shopping Cart</h2>
    <div class="row">
        <div class="col-md-8">
            <div id="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (10%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Shipping:</span>
                        <span id="shipping">$10.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                    <button class="btn btn-success w-100 mt-3" onclick="proceedToCheckout()">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadCartItems();
});

function loadCartItems() {
    $.ajax({
        url: 'api/get_cart.php',
        method: 'GET',
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                displayCartItems(result.items);
                updateCartSummary(result.items);
            }
        }
    });
}

function displayCartItems(items) {
    let html = '';
    if (items.length === 0) {
        html = '<div class="alert alert-info">Your cart is empty. <a href="products.php">Continue shopping</a></div>';
    } else {
        items.forEach(item => {
            html += `
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-3">
                            <img src="assets/images/${item.image}" class="img-fluid rounded-start h-100" alt="${item.name}">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">${item.name}</h5>
                                        <p class="card-text text-muted">$${item.price} each</p>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm" onclick="removeFromCart(${item.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <label class="form-label me-2">Quantity:</label>
                                    <input type="number" class="form-control quantity-input" style="width: 80px;" 
                                           value="${item.quantity}" min="1" 
                                           onchange="updateQuantity(${item.id}, this.value)">
                                    <span class="ms-3 fw-bold">Total: $${(item.price * item.quantity).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    $('#cart-items').html(html);
}

function updateCartSummary(items) {
    let subtotal = 0;
    items.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    
    const tax = subtotal * 0.1;
    const shipping = items.length > 0 ? 10 : 0;
    const total = subtotal + tax + shipping;
    
    $('#subtotal').text('$' + subtotal.toFixed(2));
    $('#tax').text('$' + tax.toFixed(2));
    $('#shipping').text('$' + shipping.toFixed(2));
    $('#total').text('$' + total.toFixed(2));
}

function updateQuantity(cartId, quantity) {
    $.ajax({
        url: 'api/cart_operations.php',
        method: 'POST',
        data: {
            action: 'update',
            cart_id: cartId,
            quantity: quantity
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                loadCartItems();
            }
        }
    });
}

function removeFromCart(cartId) {
    if (confirm('Are you sure you want to remove this item?')) {
        $.ajax({
            url: 'api/cart_operations.php',
            method: 'POST',
            data: {
                action: 'remove',
                cart_id: cartId
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    loadCartItems();
                    updateCartCount();
                }
            }
        });
    }
}

function proceedToCheckout() {
    window.location.href = 'payment.php';
}
</script>

<?php include 'includes/footer.php'; ?> 