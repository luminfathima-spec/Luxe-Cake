<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cakes.php');
    exit;
}

$cake_id = (int)($_POST['cake_id'] ?? 0);
$weight = trim($_POST['weight'] ?? '1kg');
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
$custom_message = trim($_POST['custom_message'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM cakes WHERE id = ? AND is_available = 1");
$stmt->execute([$cake_id]);
$cake = $stmt->fetch();

if (!$cake) {
    set_flash('danger', 'Selected cake is unavailable.');
    header('Location: ../cakes.php');
    exit;
}

// Calculate adjusted price based on weight multiplier
$multiplier = 1.0;
if ($weight === '0.5kg') $multiplier = 0.6;
elseif ($weight === '1kg') $multiplier = 1.0;
elseif ($weight === '2kg') $multiplier = 1.9;
elseif ($weight === '3kg') $multiplier = 2.8;
elseif ($weight === '5kg') $multiplier = 4.5;

$unit_price = round($cake['price'] * $multiplier, 2);

// Unique item key based on cake_id, weight, and custom message
$item_key = md5("{$cake_id}_{$weight}_{$custom_message}");

if (isset($_SESSION['cart'][$item_key])) {
    $_SESSION['cart'][$item_key]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$item_key] = [
        'cake_id' => $cake_id,
        'name' => $cake['name'],
        'image' => $cake['image'],
        'weight' => $weight,
        'unit_price' => $unit_price,
        'quantity' => $quantity,
        'custom_message' => $custom_message
    ];
}

set_flash('success', "Added {$cake['name']} ({$weight}) to your shopping bag.");
header('Location: ../cart.php');
exit;
