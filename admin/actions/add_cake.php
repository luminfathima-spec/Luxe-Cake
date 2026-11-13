<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../manage_cakes.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 1);
$flavor = trim($_POST['flavor'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$weight_options = trim($_POST['weight_options'] ?? '0.5kg, 1kg, 2kg');
$image = trim($_POST['image'] ?? 'images/cakes/chocolate-truffle.svg');
$description = trim($_POST['description'] ?? '');
$is_featured = isset($_POST['is_featured']) ? 1 : 0;
$is_available = isset($_POST['is_available']) ? 1 : 0;

$slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));

try {
    $stmt = $pdo->prepare("
        INSERT INTO cakes (category_id, name, slug, description, flavor, price, weight_options, image, is_featured, is_available)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $category_id, $name, $slug, $description, $flavor, $price, $weight_options, $image, $is_featured, $is_available
    ]);

    set_flash('success', "Cake '{$name}' created successfully.");
} catch (Exception $e) {
    set_flash('danger', "Error creating cake: " . $e->getMessage());
}

header('Location: ../manage_cakes.php');
exit;
