<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cake_id = (int)($_POST['cake_id'] ?? 0);
    try {
        $stmt = $pdo->prepare("DELETE FROM cakes WHERE id = ?");
        $stmt->execute([$cake_id]);
        set_flash('success', "Cake deleted successfully.");
    } catch (Exception $e) {
        set_flash('danger', "Error deleting cake: " . $e->getMessage());
    }
}

header('Location: ../manage_cakes.php');
exit;
