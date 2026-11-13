<?php
$page_title = "Admin Dashboard";
$page_heading = "Dashboard & Store Analytics";
require_once __DIR__ . '/includes/admin_header.php';

// Calculate analytics
$rev_stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE order_status != 'Cancelled'");
$total_revenue = $rev_stmt->fetchColumn();

$orders_count = $pdo->query("SELECT count(*) FROM orders")->fetchColumn();
$cakes_count = $pdo->query("SELECT count(*) FROM cakes")->fetchColumn();
$users_count = $pdo->query("SELECT count(*) FROM users WHERE role = 'user'")->fetchColumn();

// Order status breakdown
$pending_count = $pdo->query("SELECT count(*) FROM orders WHERE order_status = 'Pending'")->fetchColumn();
$baking_count = $pdo->query("SELECT count(*) FROM orders WHERE order_status IN ('Baking', 'Preparing')")->fetchColumn();
$delivered_count = $pdo->query("SELECT count(*) FROM orders WHERE order_status = 'Delivered'")->fetchColumn();

// Recent orders
$recent_stmt = $pdo->query("
    SELECT o.*, u.name as customer_name, u.email as customer_email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC LIMIT 8
");
$recent_orders = $recent_stmt->fetchAll();
?>

<!-- KPI Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Gross Revenue</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark">$<?php echo number_format($total_revenue, 2); ?></h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Customer Orders</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark"><?php echo $orders_count; ?></h3>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Active Cake Products</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark"><?php echo $cakes_count; ?></h3>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-cake-candles"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Registered Clients</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark"><?php echo $users_count; ?></h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kitchen Operational Pipeline Status -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="bg-white p-3 rounded-4 shadow-sm border-start border-4 border-warning">
            <span class="text-muted small fw-bold text-uppercase">Pending Review</span>
            <h4 class="fw-bold mb-0 text-warning"><?php echo $pending_count; ?> Orders</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white p-3 rounded-4 shadow-sm border-start border-4 border-info">
            <span class="text-muted small fw-bold text-uppercase">Currently in Oven / Baking</span>
            <h4 class="fw-bold mb-0 text-info"><?php echo $baking_count; ?> Orders</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white p-3 rounded-4 shadow-sm border-start border-4 border-success">
            <span class="text-muted small fw-bold text-uppercase">Fulfilled & Delivered</span>
            <h4 class="fw-bold mb-0 text-success"><?php echo $delivered_count; ?> Orders</h4>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white p-4 rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Recent Orders</h5>
        <a href="manage_orders.php" class="btn btn-sm btn-outline-secondary rounded-pill">View All Orders</a>
    </div>

    <?php if (empty($recent_orders)): ?>
        <p class="text-muted text-center py-4 mb-0">No orders placed yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>Order #</th>
                        <th>Client</th>
                        <th>Delivery Slot</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $ord): 
                        $status = $ord['order_status'];
                        $badge_class = 'bg-secondary';
                        if ($status === 'Pending') $badge_class = 'bg-warning text-dark';
                        elseif ($status === 'Baking' || $status === 'Preparing') $badge_class = 'bg-info text-dark';
                        elseif ($status === 'Out for Delivery') $badge_class = 'bg-primary';
                        elseif ($status === 'Delivered') $badge_class = 'bg-success';
                        elseif ($status === 'Cancelled') $badge_class = 'bg-danger';
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($ord['order_number']); ?></strong>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['created_at']); ?></div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($ord['delivery_name']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['delivery_phone']); ?></div>
                            </td>
                            <td>
                                <div><?php echo htmlspecialchars($ord['delivery_date']); ?></div>
                                <div class="text-muted small"><?php echo htmlspecialchars($ord['delivery_time_slot']); ?></div>
                            </td>
                            <td class="fw-bold text-dark">$<?php echo number_format($ord['total_amount'], 2); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($ord['payment_method']); ?></span>
                            </td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-2"><?php echo htmlspecialchars($status); ?></span>
                            </td>
                            <td>
                                <form action="actions/update_order_status.php" method="POST" class="d-inline-flex gap-1">
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
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
