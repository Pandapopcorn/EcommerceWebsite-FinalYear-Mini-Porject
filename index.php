<?php include 'includes/header.php'; ?>

<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4">Welcome to E-Store</h1>
                <p class="lead">Discover amazing products at unbeatable prices</p>
                <a href="products.php" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/hero-banner.jpg" alt="Hero Banner" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row" id="featured-products">
        <!-- Featured products will be loaded here -->
    </div>
</div>

<script>
$(document).ready(function() {
    loadFeaturedProducts();
});

function loadFeaturedProducts() {
    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        data: { limit: 4 },
        success: function(response) {
            const products = JSON.parse(response);
            let html = '';
            products.forEach(product => {
                html += `
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="assets/images/${product.image}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description.substring(0, 100)}...</p>
                                <p class="card-text"><strong>$${product.price}</strong></p>
                                <a href="product_detail.php?id=${product.id}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#featured-products').html(html);
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?> 