<?php include 'includes/header.php'; ?>

<div class="container my-4">
    <h2>Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <form id="payment-form">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Billing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" id="zip" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="card_number" 
                                   placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiry" 
                                       placeholder="MM/YY" maxlength="5" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" 
                                       placeholder="123" maxlength="3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cardholder Name</label>
                            <input type="text" class="form-control" id="cardholder_name" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body" id="order-summary">
                    <!-- Order summary will be loaded here -->
                </div>
                <div class="card-footer">
                    <button class="btn btn-success w-100" onclick="processPayment()">
                        <i class="fas fa-lock"></i> Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadOrderSummary();
    setupCardFormatting();
});

function loadOrderSummary() {
    $.ajax({
        url: 'api/get_cart.php',
        method: 'GET',
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                displayOrderSummary(result.items);
            }
        }
    });
}

function displayOrderSummary(items) {
    let html = '';
    let subtotal = 0;
    
    items.forEach(item => {
        subtotal += item.price * item.quantity;
        html += `
            <div class="d-flex justify-content-between mb-2">
                <span>${item.name} x${item.quantity}</span>
                <span>$${(item.price * item.quantity).toFixed(2)}</span>
            </div>
        `;
    });
    
    const tax = subtotal * 0.1;
    const shipping = 10;
    const total = subtotal + tax + shipping;
    
    html += `
        <hr>
        <div class="d-flex justify-content-between">
            <span>Subtotal:</span>
            <span>$${subtotal.toFixed(2)}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Tax:</span>
            <span>$${tax.toFixed(2)}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Shipping:</span>
            <span>$${shipping.toFixed(2)}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold">
            <span>Total:</span>
            <span>$${total.toFixed(2)}</span>
        </div>
    `;
    
    $('#order-summary').html(html);
}

function setupCardFormatting() {
    // Format card number
    $('#card_number').on('input', function() {
        let value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        $(this).val(formattedValue);
    });
    
    // Format expiry date
    $('#expiry').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        $(this).val(value);
    });
}

function processPayment() {
    // Validate form
    if (!$('#payment-form')[0].checkValidity()) {
        $('#payment-form')[0].reportValidity();
        return;
    }
    
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    button.disabled = true;
    
    // Simulate payment processing
    setTimeout(() => {
        $.ajax({
            url: 'api/process_payment.php',
            method: 'POST',
            data: {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                zip: $('#zip').val(),
                card_number: $('#card_number').val(),
                expiry: $('#expiry').val(),
                cvv: $('#cvv').val(),
                cardholder_name: $('#cardholder_name').val()
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    showAlert('Payment successful! Redirecting to confirmation...', 'success');
                    setTimeout(() => {
                        window.location.href = 'order_confirmation.php?order_id=' + result.order_id;
                    }, 2000);
                } else {
                    showAlert(result.message, 'danger');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }
        });
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?> 