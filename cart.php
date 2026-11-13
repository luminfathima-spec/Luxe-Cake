<?php
$page_title = "Your Shopping Bag";
require_once __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$shipping = $subtotal > 0 ? 5.00 : 0.00;
$total = $subtotal + $shipping;
?>

<div class="container py-5">
    <h1 class="display-6 fw-bold mb-4"><i class="fa-solid fa-bag-shopping text-warning me-2"></i> Your Shopping Bag</h1>

    <?php if (empty($cart)): ?>
        <div class="bg-white p-5 rounded-4 shadow-sm text-center">
            <i class="fa-solid fa-basket-shopping fs-1 text-muted mb-3"></i>
            <h3 class="fw-bold mb-2">Your Bag is Empty</h3>
            <p class="text-muted mb-4">Discover our handcrafted confections and find your perfect celebration cake.</p>
            <a href="cakes.php" class="btn btn-luxury px-4 py-2">Browse Cakes</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-uppercase small text-muted border-bottom">
                                <tr>
                                    <th>Cake</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $item): 
                                    $line_total = $item['unit_price'] * $item['quantity'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                    <span class="badge bg-light text-dark border small me-1">Size: <?php echo htmlspecialchars($item['weight']); ?></span>
                                                    <?php if (!empty($item['custom_message'])): ?>
                                                        <div class="small text-muted mt-1">
                                                            <i class="fa-solid fa-pen-nib text-warning me-1"></i> "<?php echo htmlspecialchars($item['custom_message']); ?>"
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold">$<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <form action="actions/update_cart.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="item_key" value="<?php echo $key; ?>">
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 28px; height: 28px; padding: 0;">-</button>
                                                </form>
                                                <span class="fw-bold px-2"><?php echo $item['quantity']; ?></span>
                                                <form action="actions/update_cart.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="item_key" value="<?php echo $key; ?>">
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 28px; height: 28px; padding: 0;">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">$<?php echo number_format($line_total, 2); ?></td>
                                        <td>
                                            <form action="actions/remove_from_cart.php" method="POST">
                                                <input type="hidden" name="item_key" value="<?php echo $key; ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Remove Item">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="cakes.php" class="text-decoration-none text-muted small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Chilled Courier Delivery</span>
                        <span class="fw-semibold">$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold h5 mb-0">Total</span>
                        <span class="display-6 fw-bold text-warning mb-0">$<?php echo number_format($total, 2); ?></span>
                    </div>

                    <a href="checkout.php" class="btn btn-luxury w-100 py-3 mb-3">
                        Proceed to Checkout <i class="fa-solid fa-lock ms-2"></i>
                    </a>

                    <div class="small text-muted text-center">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Secure 256-bit Encrypted Checkout
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
