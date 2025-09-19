<?php
include 'includes/header.php';

$product_id = $_GET['id'] ?? 0;
if (!$product_id) {
    header('Location: products.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM products WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: products.php');
    exit;
}
?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-6">
            <img src="assets/images/<?php echo $product['image']; ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-muted"><?php echo htmlspecialchars($product['category']); ?></p>
            
            <div class="mb-3">
                <span class="badge bg-warning">★ <?php echo $product['rating']; ?></span>
                <span class="text-muted ms-2">(Based on customer reviews)</span>
            </div>
            
            <h3 class="text-primary">$<?php echo $product['price']; ?></h3>
            
            <div class="mb-4">
                <h5>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 100px;">
            </div>
            
            <div class="mb-3">
                <span class="text-success">In Stock: <?php echo $product['stock_quantity']; ?> items</span>
            </div>
            
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-lg" onclick="addToCart(<?php echo $product['id']; ?>)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
                <button class="btn btn-success btn-lg" onclick="buyNow(<?php echo $product['id']; ?>)">
                    <i class="fas fa-bolt"></i> Buy Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    const quantity = $('#quantity').val();
    
    $.ajax({
        url: 'api/cart_operations.php',
        method: 'POST',
        data: {
            action: 'add',
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                showAlert('Product added to cart successfully!', 'success');
                updateCartCount();
            } else {
                showAlert(result.message, 'danger');
            }
        }
    });
}

function buyNow(productId) {
    addToCart(productId);
    setTimeout(() => {
        window.location.href = 'cart.php';
    }, 1000);
}
</script>

<?php include 'includes/footer.php'; ?> 