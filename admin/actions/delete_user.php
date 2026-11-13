<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $admin = current_user();

    if ($user_id == $admin['id']) {
        set_flash('danger', "You cannot delete your own admin account.");
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            set_flash('success', "User account deleted successfully.");
        } catch (Exception $e) {
            set_flash('danger', "Error deleting user: " . $e->getMessage());
        }
    }
}

header('Location: ../manage_users.php');
exit;
