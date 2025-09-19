<?php include 'includes/header.php'; ?>

<div class="container my-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h4 class="text-center">Register</h4>
				</div>
				<div class="card-body">
					<form id="register-form">
						<div class="mb-3">
							<label class="form-label">Full Name</label>
							<input type="text" class="form-control" id="name" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Email</label>
							<input type="email" class="form-control" id="email" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Phone</label>
							<input type="text" class="form-control" id="phone">
						</div>
						<div class="mb-3">
							<label class="form-label">Password</label>
							<input type="password" class="form-control" id="password" required minlength="6">
						</div>
						<div class="mb-3">
							<label class="form-label">Confirm Password</label>
							<input type="password" class="form-control" id="confirm_password" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Address</label>
							<textarea class="form-control" id="address" rows="3"></textarea>
						</div>
						<button type="submit" class="btn btn-primary w-100">Register</button>
					</form>
					<div class="text-center mt-3">
						<p>Already have an account? <a href="login.php">Login here</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$('#register-form').on('submit', function(e) {
	e.preventDefault();
	
	const password = $('#password').val();
	const confirmPassword = $('#confirm_password').val();
	
	if (password !== confirmPassword) {
		showAlert('Passwords do not match!', 'danger');
		return;
	}
	
	$.ajax({
		url: 'api/register.php',
		method: 'POST',
		data: {
			name: $('#name').val(),
			email: $('#email').val(),
			phone: $('#phone').val(),
			password: password,
			address: $('#address').val()
		},
		success: function(response) {
			const result = JSON.parse(response);
			if (result.success) {
				showAlert('Registration successful! Please login.', 'success');
				setTimeout(() => {
					window.location.href = 'login.php';
				}, 2000);
			} else {
				showAlert(result.message, 'danger');
			}
		}
	});
});
</script>

<?php include 'includes/footer.php'; ?> 