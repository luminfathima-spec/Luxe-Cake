<?php
$page_title = "Manage Customer Orders";
$page_heading = "Customer Orders & Dispatch Pipeline";
require_once __DIR__ . '/includes/admin_header.php';

$filter_status = $_GET['status'] ?? 'all';

$query = "
    SELECT o.*, u.name as customer_name, u.email as customer_email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id
";
$params = [];

if ($filter_status !== 'all') {
    $query .= " WHERE o.order_status = ?";
    $params[] = $filter_status;
}
$query .= " ORDER BY o.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<!-- Status Filter Tabs -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="manage_orders.php?status=all" class="btn btn-sm <?php echo $filter_status === 'all' ? 'btn-dark' : 'btn-outline-secondary'; ?> rounded-pill px-3">All Orders</a>
    <a href="manage_orders.php?status=Pending" class="btn btn-sm <?php echo $filter_status === 'Pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark'; ?> rounded-pill px-3">Pending</a>
    <a href="manage_orders.php?status=Baking" class="btn btn-sm <?php echo $filter_status === 'Baking' ? 'btn-info text-dark' : 'btn-outline-info text-dark'; ?> rounded-pill px-3">In Kitchen (Baking)</a>
    <a href="manage_orders.php?status=Out for Delivery" class="btn btn-sm <?php echo $filter_status === 'Out for Delivery' ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-3">Out for Delivery</a>
    <a href="manage_orders.php?status=Delivered" class="btn btn-sm <?php echo $filter_status === 'Delivered' ? 'btn-success' : 'btn-outline-success'; ?> rounded-pill px-3">Delivered</a>
    <a href="manage_orders.php?status=Cancelled" class="btn btn-sm <?php echo $filter_status === 'Cancelled' ? 'btn-danger' : 'btn-outline-danger'; ?> rounded-pill px-3">Cancelled</a>
</div>

<div class="bg-white p-4 rounded-4 shadow-sm">
    <?php if (empty($orders)): ?>
        <p class="text-muted text-center py-4 mb-0">No orders found matching the filter.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer</th>
                        <th>Delivery Scheduled</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $ord): 
                        $status = $ord['order_status'];
                        $badge_class = 'bg-secondary';
                        if ($status === 'Pending') $badge_class = 'bg-warning text-dark';
                        elseif ($status === 'Baking' || $status === 'Preparing') $badge_class = 'bg-info text-dark';
                        elseif ($status === 'Out for Delivery') $badge_class = 'bg-primary';
                        elseif ($status === 'Delivered') $badge_class = 'bg-success';
                        elseif ($status === 'Cancelled') $badge_class = 'bg-danger';

                        // Fetch items for this order
                        $i_stmt = $pdo->prepare("SELECT oi.*, c.name FROM order_items oi JOIN cakes c ON oi.cake_id = c.id WHERE oi.order_id = ?");
                        $i_stmt->execute([$ord['id']]);
                        $order_items = $i_stmt->fetchAll();
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($ord['order_number']); ?></strong>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['created_at']); ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($ord['delivery_name']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['delivery_phone']); ?></div>
                                <div class="text-muted small" style="max-width: 200px;"><?php echo htmlspecialchars($ord['delivery_address']); ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($ord['delivery_date']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['delivery_time_slot']); ?></div>
                            </td>
                            <td class="fw-bold text-dark">$<?php echo number_format($ord['total_amount'], 2); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($ord['payment_method']); ?></span>
                                <div class="small text-muted"><?php echo htmlspecialchars($ord['payment_status']); ?></div>
                            </td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-2"><?php echo htmlspecialchars($status); ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-toggle="collapse" data-bs-target="#items_<?php echo $ord['id']; ?>">
                                    <?php echo count($order_items); ?> cakes <i class="fa-solid fa-chevron-down ms-1"></i>
                                </button>
                            </td>
                            <td>
                                <form action="actions/update_order_status.php" method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                    <select name="new_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Baking" <?php echo $status === 'Baking' ? 'selected' : ''; ?>>Baking</option>
                                        <option value="Out for Delivery" <?php echo $status === 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                        <option value="Delivered" <?php echo $status === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="Cancelled" <?php echo $status === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <tr class="collapse bg-light" id="items_<?php echo $ord['id']; ?>">
                            <td colspan="8" class="p-3">
                                <div class="p-3 bg-white rounded-3 border">
                                    <h6 class="fw-bold mb-2">Order Line Items:</h6>
                                    <ul class="mb-0 small">
                                        <?php foreach ($order_items as $item): ?>
                                            <li class="mb-1">
                                                <strong><?php echo htmlspecialchars($item['name']); ?></strong> 
                                                (<?php echo htmlspecialchars($item['weight']); ?>) × <?php echo $item['quantity']; ?> — 
                                                $<?php echo number_format($item['price'], 2); ?>
                                                <?php if (!empty($item['custom_message'])): ?>
                                                    <span class="text-warning"> [Piped text: "<?php echo htmlspecialchars($item['custom_message']); ?>"]</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php if (!empty($ord['special_instructions'])): ?>
                                        <div class="mt-2 text-muted small"><strong>Special Instructions:</strong> <?php echo htmlspecialchars($ord['special_instructions']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
