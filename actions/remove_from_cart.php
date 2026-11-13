<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_key = $_POST['item_key'] ?? '';
    if (isset($_SESSION['cart'][$item_key])) {
        $name = $_SESSION['cart'][$item_key]['name'];
        unset($_SESSION['cart'][$item_key]);
        set_flash('info', "Removed {$name} from shopping bag.");
    }
}

header('Location: ../cart.php');
exit;
