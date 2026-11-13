<?php
require_once __DIR__ . '/includes/auth.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$page_title = "Sign In";
$redirect = $_GET['redirect'] ?? 'index.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="mx-auto bg-white p-5 rounded-4 shadow-sm border" style="max-width: 480px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">Welcome Back</h2>
            <p class="text-muted small">Sign in to manage your cake orders, track deliveries, and save favorite flavors.</p>
        </div>

        <form action="actions/user_login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-regular fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-luxury w-100 py-3 mb-3">Sign In</button>
        </form>

        <!-- Quick Demo Credentials Box -->
        <div class="bg-light p-3 rounded-3 mb-4 small border">
            <div class="fw-bold text-muted mb-1"><i class="fa-solid fa-key text-warning me-1"></i> Quick Test Credentials:</div>
            <div><strong>Customer:</strong> sarah@example.com / <code>user123</code></div>
            <div><strong>Administrator:</strong> admin@luxecake.com / <code>admin123</code></div>
        </div>

        <div class="text-center small text-muted">
            Don't have an account? <a href="register.php" class="text-warning fw-semibold text-decoration-none">Create an Account</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
