<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

require_login('login.php?redirect=my_orders.php');

$user = current_user();
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

$page_title = "My Orders";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold mb-0"><i class="fa-solid fa-receipt text-warning me-2"></i> My Order History</h1>
        <a href="cakes.php" class="btn btn-outline-luxury">Order New Cake</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white p-5 rounded-4 shadow-sm text-center">
            <i class="fa-solid fa-cookie-bite fs-1 text-muted mb-3"></i>
            <h4 class="fw-bold">No orders placed yet</h4>
            <p class="text-muted">You haven't ordered any cakes yet. Choose from our delicious confections!</p>
            <a href="cakes.php" class="btn btn-luxury mt-2">Explore Catalog</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($orders as $order): 
                $status = $order['order_status'];
                $badge_class = 'bg-secondary';
                if ($status === 'Pending') $badge_class = 'bg-warning text-dark';
                elseif ($status === 'Baking' || $status === 'Preparing') $badge_class = 'bg-info text-dark';
                elseif ($status === 'Out for Delivery') $badge_class = 'bg-primary';
                elseif ($status === 'Delivered') $badge_class = 'bg-success';
                elseif ($status === 'Cancelled') $badge_class = 'bg-danger';
            ?>
                <div class="col-12">
                    <div class="bg-white p-4 rounded-4 shadow-sm border">
                        <div class="row align-items-center g-3">
                            <div class="col-md-3">
                                <span class="text-muted small text-uppercase d-block">Order #</span>
                                <strong class="fs-5 text-dark"><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                <div class="text-muted small mt-1"><?php echo htmlspecialchars($order['created_at']); ?></div>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted small text-uppercase d-block">Delivery Scheduled</span>
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($order['delivery_date']); ?></span>
                                <div class="text-muted small"><?php echo htmlspecialchars($order['delivery_time_slot']); ?></div>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small text-uppercase d-block">Total Amount</span>
                                <span class="fw-bold fs-5 text-warning">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small text-uppercase d-block">Status</span>
                                <span class="badge <?php echo $badge_class; ?> px-3 py-2 rounded-pill"><?php echo htmlspecialchars($status); ?></span>
                            </div>
                            <div class="col-md-2 text-md-end">
                                <a href="order_confirmation.php?order=<?php echo htmlspecialchars($order['order_number']); ?>" class="btn btn-outline-luxury btn-sm px-3">
                                    View Receipt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
