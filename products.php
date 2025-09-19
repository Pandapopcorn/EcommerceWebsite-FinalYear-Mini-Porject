<?php include 'includes/header.php'; ?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="category-filter">
                            <option value="">All Categories</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Accessories">Accessories</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price Range</label>
                        <input type="range" class="form-range" id="price-range" min="0" max="2000" value="2000">
                        <span id="price-value">$2000</span>
                    </div>
                    <button class="btn btn-primary w-100" onclick="filterProducts()">Apply Filters</button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Products</h2>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="search-input" placeholder="Search products...">
                    <button class="btn btn-outline-secondary" onclick="searchProducts()">Search</button>
                </div>
            </div>
            <div class="row" id="products-container">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadProducts();
    
    $('#price-range').on('input', function() {
        $('#price-value').text('$' + $(this).val());
    });
});

function loadProducts() {
    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        success: function(response) {
            const products = JSON.parse(response);
            displayProducts(products);
        }
    });
}

function displayProducts(products) {
    let html = '';
    products.forEach(product => {
        html += `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="assets/images/${product.image}" onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text=No+Image';" class="card-img-top" alt="${product.name}">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description.substring(0, 100)}...</p>
                        <p class="card-text">
                            <strong>$${product.price}</strong>
                            <span class="badge bg-warning ms-2">★ ${product.rating}</span>
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="product_detail.php?id=${product.id}" class="btn btn-outline-primary">View Details</a>
                            <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    $('#products-container').html(html);
}

function filterProducts() {
    const category = $('#category-filter').val();
    const maxPrice = $('#price-range').val();
    
    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        data: { category: category, max_price: maxPrice },
        success: function(response) {
            const products = JSON.parse(response);
            displayProducts(products);
        }
    });
}

function searchProducts() {
    const search = $('#search-input').val();
    
    $.ajax({
        url: 'api/get_products.php',
        method: 'GET',
        data: { search: search },
        success: function(response) {
            const products = JSON.parse(response);
            displayProducts(products);
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?> 