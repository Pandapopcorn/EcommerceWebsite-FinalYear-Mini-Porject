<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = $_GET['order_id'];
$database = new Database();
$db = $database->getConnection();

// Get order details
$query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: index.php');
    exit;
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h2 class="mt-3">Order Confirmed!</h2>
                    <p class="lead">Thank you for your purchase. Your order has been successfully placed.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Order Details</h5>
                            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                            <p><strong>Total Amount:</strong> $<?php echo $order['total_amount']; ?></p>
                            <p><strong>Payment Status:</strong> <?php echo ucfirst($order['payment_status']); ?></p>
                            <p><strong>Order Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>What's Next?</h5>
                            <p>• You will receive an email confirmation shortly</p>
                            <p>• Your order will be processed within 24 hours</p>
                            <p>• Shipping typically takes 3-5 business days</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="products.php" class="btn btn-primary me-2">Continue Shopping</a>
                        <a href="profile.php" class="btn btn-outline-primary">View Order History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 