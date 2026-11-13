<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

require_login('login.php');

$order_number = $_GET['order'] ?? '';
$user = current_user();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$stmt->execute([$order_number, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    set_flash('danger', 'Order not found.');
    header('Location: my_orders.php');
    exit;
}

$items_stmt = $pdo->prepare("
    SELECT oi.*, c.name, c.image, c.flavor 
    FROM order_items oi 
    JOIN cakes c ON oi.cake_id = c.id 
    WHERE oi.order_id = ?
");
$items_stmt->execute([$order['id']]);
$items = $items_stmt->fetchAll();

$page_title = "Order Confirmation — " . $order['order_number'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="max-w-750 mx-auto bg-white p-5 rounded-4 shadow-sm border" style="max-width: 800px;">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-circle-check fs-1"></i>
            </div>
            <h1 class="h3 fw-bold mb-1">Thank You! Your Order is Confirmed</h1>
            <p class="text-muted mb-0">We have received your celebration order and our pastry chefs are preparing your bake.</p>
        </div>

        <div class="bg-light p-4 rounded-4 mb-4">
            <div class="row g-3">
                <div class="col-sm-6">
                    <span class="text-muted small text-uppercase d-block">Order Reference</span>
                    <strong class="fs-5 text-dark"><?php echo htmlspecialchars($order['order_number']); ?></strong>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-muted small text-uppercase d-block">Order Status</span>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><?php echo htmlspecialchars($order['order_status']); ?></span>
                </div>
                <div class="col-sm-6">
                    <span class="text-muted small text-uppercase d-block">Scheduled Delivery Date</span>
                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($order['delivery_date']); ?> (<?php echo htmlspecialchars($order['delivery_time_slot']); ?>)</span>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-muted small text-uppercase d-block">Payment Method</span>
                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($order['payment_method']); ?> (<?php echo htmlspecialchars($order['payment_status']); ?>)</span>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Itemized Confections</h5>
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="width: 60px;">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="small text-muted">Weight: <?php echo htmlspecialchars($item['weight']); ?> | Qty: <?php echo $item['quantity']; ?></div>
                                <?php if (!empty($item['custom_message'])): ?>
                                    <div class="small text-warning fst-italic">Piped text: "<?php echo htmlspecialchars($item['custom_message']); ?>"</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end text-muted">Delivery:</td>
                        <td class="text-end fw-semibold">$5.00</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end fw-bold fs-5">Total Paid / Due:</td>
                        <td class="text-end fw-bold fs-5 text-warning">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="border-top pt-4 mb-4">
            <h6 class="fw-bold mb-2">Delivery Address</h6>
            <p class="text-muted mb-1"><?php echo htmlspecialchars($order['delivery_name']); ?> (<?php echo htmlspecialchars($order['delivery_phone']); ?>)</p>
            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></p>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="my_orders.php" class="btn btn-outline-secondary rounded-pill px-4">View All Orders</a>
            <button onclick="window.print()" class="btn btn-luxury rounded-pill px-4"><i class="fa-solid fa-print me-2"></i> Print Invoice</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
