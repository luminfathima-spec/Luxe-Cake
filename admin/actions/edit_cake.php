<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../manage_cakes.php');
    exit;
}

$cake_id = (int)($_POST['cake_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 1);
$flavor = trim($_POST['flavor'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$weight_options = trim($_POST['weight_options'] ?? '0.5kg, 1kg, 2kg');
$image = trim($_POST['image'] ?? 'images/cakes/chocolate-truffle.svg');
$description = trim($_POST['description'] ?? '');
$is_featured = isset($_POST['is_featured']) ? 1 : 0;
$is_available = isset($_POST['is_available']) ? 1 : 0;

try {
    $stmt = $pdo->prepare("
        UPDATE cakes SET 
            category_id = ?, name = ?, description = ?, flavor = ?, 
            price = ?, weight_options = ?, image = ?, is_featured = ?, is_available = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $category_id, $name, $description, $flavor, 
        $price, $weight_options, $image, $is_featured, $is_available, $cake_id
    ]);

    set_flash('success', "Updated cake '{$name}' successfully.");
} catch (Exception $e) {
    set_flash('danger', "Error updating cake: " . $e->getMessage());
}

header('Location: ../manage_cakes.php');
exit;
