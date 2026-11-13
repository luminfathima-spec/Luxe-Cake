<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

require_login('login.php?redirect=checkout.php');

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    set_flash('warning', 'Your shopping bag is empty.');
    header('Location: cakes.php');
    exit;
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$shipping = 5.00;
$total = $subtotal + $shipping;

// Fetch current user details
$user = current_user();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$user_info = $stmt->fetch();

$page_title = "Checkout";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <h1 class="display-6 fw-bold mb-4">Complete Your Order</h1>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-truck-ramp-box text-warning me-2"></i> Delivery Details</h5>

                <form action="actions/place_order.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Recipient Full Name</label>
                            <input type="text" name="delivery_name" class="form-control" value="<?php echo htmlspecialchars($user_info['name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Contact Phone</label>
                            <input type="tel" name="delivery_phone" class="form-control" value="<?php echo htmlspecialchars($user_info['phone'] ?? ''); ?>" placeholder="+91 98765 43210" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Delivery Street Address</label>
                            <textarea name="delivery_address" rows="3" class="form-control" placeholder="House/Flat No, Apartment, Landmark, City & PIN Code" required><?php echo htmlspecialchars($user_info['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase">Delivery Time Slot</label>
                            <select name="delivery_time_slot" class="form-select" required>
                                <option value="Morning (09:00 AM - 12:00 PM)">Morning (09:00 AM - 12:00 PM)</option>
                                <option value="Afternoon (12:00 PM - 04:00 PM)">Afternoon (12:00 PM - 04:00 PM)</option>
                                <option value="Evening (04:00 PM - 08:00 PM)" selected>Evening (04:00 PM - 08:00 PM)</option>
                                <option value="Late Night Surprise (11:00 PM - 12:00 AM)">Late Night Surprise (11:00 PM - 12:00 AM)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-uppercase">Special Delivery Instructions / Dietary Notes</label>
                            <input type="text" name="special_instructions" class="form-control" placeholder="e.g. Leave with security, ring bell twice, 100% eggless">
                        </div>
                    </div>

                    <h5 class="fw-bold mt-5 mb-3"><i class="fa-solid fa-credit-card text-warning me-2"></i> Payment Method</h5>
                    <div class="border rounded-3 p-3 mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="Cash on Delivery" checked>
                            <label class="form-check-label fw-semibold" for="payCod">
                                Cash on Delivery (COD) / Pay on Handover
                            </label>
                            <div class="text-muted small">Pay with cash or UPI QR upon receiving your refrigerated delivery box.</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payOnline" value="Instant Card / NetBanking">
                            <label class="form-check-label fw-semibold" for="payOnline">
                                Instant Card / NetBanking / UPI (Simulated Gateway)
                            </label>
                            <div class="text-muted small">Fast digital payment with automated instant order confirmation.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-luxury w-100 py-3 fs-5">
                        Place Order • $<?php echo number_format($total, 2); ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h5 class="fw-bold mb-3">Items in Bag (<?php echo count($cart); ?>)</h5>
                <ul class="list-unstyled mb-4">
                    <?php foreach ($cart as $item): ?>
                        <li class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <div class="fw-semibold small"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <span class="text-muted small"><?php echo htmlspecialchars($item['weight']); ?> × <?php echo $item['quantity']; ?></span>
                                    <?php if (!empty($item['custom_message'])): ?>
                                        <div class="small text-muted fst-italic">"<?php echo htmlspecialchars($item['custom_message']); ?>"</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="fw-bold small">$<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Chilled Courier Delivery</span>
                    <span class="fw-semibold">$<?php echo number_format($shipping, 2); ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold h5 mb-0">Total Due</span>
                    <span class="display-6 fw-bold text-warning mb-0">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
