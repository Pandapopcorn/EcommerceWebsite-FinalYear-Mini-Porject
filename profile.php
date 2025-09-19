<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Get user information
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Profile Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action active" onclick="showSection('profile-info')">
                        <i class="fas fa-user"></i> Profile Information
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="showSection('order-history')">
                        <i class="fas fa-shopping-bag"></i> Order History
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="showSection('change-password')">
                        <i class="fas fa-key"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Profile Information Section -->
            <div id="profile-info" class="profile-section">
                <div class="card">
                    <div class="card-header">
                        <h5>Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="profile-form">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateProfile()">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Order History Section -->
            <div id="order-history" class="profile-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h5>Order History</h5>
                    </div>
                    <div class="card-body">
                        <div id="orders-container">
                            <!-- Orders will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div id="change-password" class="profile-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form id="password-form">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" required>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadOrderHistory();
});

function showSection(sectionId) {
    $('.profile-section').hide();
    $('#' + sectionId).show();
    
    $('.list-group-item').removeClass('active');
    $('[onclick="showSection(\'' + sectionId + '\')"]').addClass('active');
    
    if (sectionId === 'order-history') {
        loadOrderHistory();
    }
}

function updateProfile() {
    $.ajax({
        url: 'api/update_profile.php',
        method: 'POST',
        data: {
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val()
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                showAlert('Profile updated successfully!', 'success');
            } else {
                showAlert(result.message, 'danger');
            }
        }
    });
}

function loadOrderHistory() {
    $.ajax({
        url: 'api/get_orders.php',
        method: 'GET',
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                displayOrders(result.orders);
            }
        }
    });
}

function displayOrders(orders) {
    let html = '';
    if (orders.length === 0) {
        html = '<div class="alert alert-info">No orders found.</div>';
    } else {
        orders.forEach(order => {
            html += `
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <span>Order #${order.id}</span>
                        <span class="badge bg-${order.order_status === 'completed' ? 'success' : 'warning'}">${order.order_status}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                        <p><strong>Total:</strong> ${order.total_amount}</p>
                        <p><strong>Payment Status:</strong> ${order.payment_status}</p>
                        <button class="btn btn-outline-primary btn-sm" onclick="viewOrderDetails(${order.id})">View Details</button>
                    </div>
                </div>
            `;
        });
    }
    $('#orders-container').html(html);
}

function changePassword() {
    const newPassword = $('#new_password').val();
    const confirmPassword = $('#confirm_password').val();
    
    if (newPassword !== confirmPassword) {
        showAlert('New passwords do not match!', 'danger');
        return;
    }
    
    $.ajax({
        url: 'api/change_password.php',
        method: 'POST',
        data: {
            current_password: $('#current_password').val(),
            new_password: newPassword
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                showAlert('Password changed successfully!', 'success');
                $('#password-form')[0].reset();
            } else {
                showAlert(result.message, 'danger');
            }
        }
    });
}

function viewOrderDetails(orderId) {
    window.location.href = 'order_details.php?id=' + orderId;
}
</script>

<?php include 'includes/footer.php'; ?> 