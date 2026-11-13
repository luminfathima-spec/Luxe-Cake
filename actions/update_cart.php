<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_key = $_POST['item_key'] ?? '';
    $action = $_POST['action'] ?? '';

    if (isset($_SESSION['cart'][$item_key])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$item_key]['quantity'] += 1;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$item_key]['quantity'] -= 1;
            if ($_SESSION['cart'][$item_key]['quantity'] <= 0) {
                unset($_SESSION['cart'][$item_key]);
            }
        }
    }
}

header('Location: ../cart.php');
exit;
