<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('danger', 'Invalid security token.');
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? '../index.php';

if (empty($email) || empty($password)) {
    set_flash('danger', 'Please enter your email and password.');
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    set_flash('success', "Welcome back, " . htmlspecialchars($user['name']) . "!");

    if ($user['role'] === 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header("Location: $redirect");
    }
    exit;
} else {
    set_flash('danger', 'Invalid email address or password.');
    header('Location: ../login.php');
    exit;
}
