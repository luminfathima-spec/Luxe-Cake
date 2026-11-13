<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('danger', 'Invalid security token.');
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$address = trim($_POST['address'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    set_flash('danger', 'Please provide name, email, and password.');
    header('Location: ../register.php');
    exit;
}

if (strlen($password) < 6) {
    set_flash('danger', 'Password must be at least 6 characters long.');
    header('Location: ../register.php');
    exit;
}

// Check email uniqueness
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    set_flash('danger', 'An account with this email address already exists.');
    header('Location: ../register.php');
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$verification_token = bin2hex(random_bytes(24));

try {
    $insert = $pdo->prepare("
        INSERT INTO users (name, email, password, phone, address, role, is_verified, verification_token)
        VALUES (?, ?, ?, ?, ?, 'user', 1, ?)
    ");
    $insert->execute([$name, $email, $hashed_password, $phone, $address, $verification_token]);

    $new_id = $pdo->lastInsertId();
    $_SESSION['user_id'] = $new_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'user';

    set_flash('success', "Registration successful! Welcome to Luxe Cake, {$name}.");
    header('Location: ../index.php');
    exit;

} catch (Exception $e) {
    set_flash('danger', 'Registration error: ' . $e->getMessage());
    header('Location: ../register.php');
    exit;
}
