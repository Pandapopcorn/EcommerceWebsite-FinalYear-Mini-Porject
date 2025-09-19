<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: profile.php');
    exit;
}

$order_id = $_GET['id'];
$database = new Database();
$db = $database->getConnection();

// Get order details
$query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: profile.php');
    exit;
}

// Get order items
$query = "SELECT oi.*, p.name, p.image FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Details #<?php echo $order['id']; ?></h2>
        <a href="profile.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($order_items as $item): ?>
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-2">
                                <img src="assets/images/<?php echo $item['image']; ?>" 
                                     class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                <p class="text-muted">Price: $<?php echo $item['price']; ?></p>
                            </div>
                            <div class="col-md-2">
                                <p>Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="col-md-2">
                                <p><strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order ID:</span>
                        <span>#<?php echo $order['id']; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Date:</span>
                        <span><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Status:</span>
                        <span class="badge bg-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Status:</span>
                        <span class="badge bg-<?php echo $order['order_status'] === 'completed' ? 'success' : 'info'; ?>">
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Amount:</span>
                        <span>$<?php echo $order['total_amount']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 