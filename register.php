<?php
require_once __DIR__ . '/includes/auth.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$page_title = "Create Your Account";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="mx-auto bg-white p-5 rounded-4 shadow-sm border" style="max-width: 540px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">Create an Account</h2>
            <p class="text-muted small">Join Luxe Cake to enjoy expedited checkout, celebration reminders, and special member tastings.</p>
        </div>

        <form action="actions/user_register.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Ameenul Lumin" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-uppercase">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Password</label>
                <input type="password" name="password" class="form-control" placeholder="At least 6 characters" minlength="6" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase">Default Delivery Address (Optional)</label>
                <textarea name="address" rows="2" class="form-control" placeholder="Street address, apartment, city"></textarea>
            </div>

            <button type="submit" class="btn btn-luxury w-100 py-3 mb-3">Register Account</button>
        </form>

        <div class="text-center small text-muted">
            Already have an account? <a href="login.php" class="text-warning fw-semibold text-decoration-none">Sign In</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
