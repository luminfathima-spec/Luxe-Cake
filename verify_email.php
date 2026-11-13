<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$token = trim($_GET['token'] ?? '');
$success = false;
$message = "";

if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        $update->execute([$user['id']]);

        // Auto log in verified user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        $success = true;
        $message = "Your email has been successfully verified! Welcome to Luxe Cake.";
    } else {
        $message = "Invalid or expired verification token.";
    }
} else {
    $message = "No verification token provided.";
}

$page_title = "Email Verification";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5 text-center">
    <div class="mx-auto bg-white p-5 rounded-4 shadow-sm border" style="max-width: 520px;">
        <?php if ($success): ?>
            <i class="fa-solid fa-circle-check fs-1 text-success mb-3"></i>
            <h2 class="fw-bold mb-2">Email Verified</h2>
            <p class="text-muted mb-4"><?php echo htmlspecialchars($message); ?></p>
            <a href="cakes.php" class="btn btn-luxury px-4 py-2">Explore Cakes</a>
        <?php else: ?>
            <i class="fa-solid fa-circle-xmark fs-1 text-danger mb-3"></i>
            <h2 class="fw-bold mb-2">Verification Failed</h2>
            <p class="text-muted mb-4"><?php echo htmlspecialchars($message); ?></p>
            <a href="login.php" class="btn btn-outline-luxury px-4 py-2">Return to Login</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
