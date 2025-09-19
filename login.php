<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Login</h4>
                </div>
                <div class="card-body">
                    <form id="login-form">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#login-form').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'api/login.php',
        method: 'POST',
        data: {
            email: $('#email').val(),
            password: $('#password').val()
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                showAlert('Login successful!', 'success');
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            } else {
                showAlert(result.message, 'danger');
            }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?> 