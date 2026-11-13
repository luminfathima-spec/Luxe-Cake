<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_login('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../checkout.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('danger', 'Invalid security token. Please try again.');
    header('Location: ../checkout.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    set_flash('warning', 'Your shopping bag is empty.');
    header('Location: ../cakes.php');
    exit;
}

$user = current_user();
$delivery_name = trim($_POST['delivery_name'] ?? '');
$delivery_phone = trim($_POST['delivery_phone'] ?? '');
$delivery_address = trim($_POST['delivery_address'] ?? '');
$delivery_date = trim($_POST['delivery_date'] ?? '');
$delivery_time_slot = trim($_POST['delivery_time_slot'] ?? '');
$special_instructions = trim($_POST['special_instructions'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? 'Cash on Delivery');

if (empty($delivery_name) || empty($delivery_phone) || empty($delivery_address) || empty($delivery_date)) {
    set_flash('danger', 'Please fill in all mandatory delivery information.');
    header('Location: ../checkout.php');
    exit;
}

// Calculate totals
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$shipping = 5.00;
$total_amount = $subtotal + $shipping;

$order_number = 'LUXE-' . strtoupper(bin2hex(random_bytes(4)));

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO orders (
            order_number, user_id, total_amount, payment_method, payment_status,
            order_status, delivery_name, delivery_phone, delivery_address,
            delivery_date, delivery_time_slot, special_instructions
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $payment_status = ($payment_method === 'Cash on Delivery') ? 'Pending' : 'Paid';
    $order_status = 'Pending';

    $stmt->execute([
        $order_number, $user['id'], $total_amount, $payment_method, $payment_status,
        $order_status, $delivery_name, $delivery_phone, $delivery_address,
        $delivery_date, $delivery_time_slot, $special_instructions
    ]);

    $order_id = $pdo->lastInsertId();

    $item_stmt = $pdo->prepare("
        INSERT INTO order_items (order_id, cake_id, quantity, weight, price, custom_message)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
        $item_stmt->execute([
            $order_id,
            $item['cake_id'],
            $item['quantity'],
            $item['weight'],
            $item['unit_price'],
            $item['custom_message']
        ]);
    }

    $pdo->commit();

    // Clear cart
    $_SESSION['cart'] = [];
    set_flash('success', "Order placed successfully! Order reference: {$order_number}");
    header("Location: ../order_confirmation.php?order={$order_number}");
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    set_flash('danger', "Error placing order: " . $e->getMessage());
    header('Location: ../checkout.php');
    exit;
}
