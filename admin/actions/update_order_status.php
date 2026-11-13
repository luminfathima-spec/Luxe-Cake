<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $new_status = trim($_POST['new_status'] ?? 'Pending');

    $allowed = ['Pending', 'Baking', 'Preparing', 'Out for Delivery', 'Delivered', 'Cancelled'];
    if (in_array($new_status, $allowed)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
            $stmt->execute([$new_status, $order_id]);
            set_flash('success', "Order #{$order_id} status updated to '{$new_status}'.");
        } catch (Exception $e) {
            set_flash('danger', "Error updating order status: " . $e->getMessage());
        }
    }
}

$ref = $_SERVER['HTTP_REFERER'] ?? '../manage_orders.php';
header("Location: $ref");
exit;
