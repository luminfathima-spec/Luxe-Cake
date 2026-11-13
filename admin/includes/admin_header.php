<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

require_admin('../../login.php');

$current_page = basename($_SERVER['PHP_SELF']);
$admin = current_user();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " | Luxe Admin" : "Luxe Cake — Control Center"; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse" id="sidebarMenu">
            <div class="d-flex align-items-center gap-2 mb-4 px-2">
                <i class="fa-solid fa-crown text-warning fs-4"></i>
                <h5 class="mb-0 text-white fw-bold">LUXE ADMIN</h5>
            </div>

            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="index.php">
                        <i class="fa-solid fa-chart-pie me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'manage_cakes.php' ? 'active' : ''; ?>" href="manage_cakes.php">
                        <i class="fa-solid fa-cake-candles me-2"></i> Manage Cakes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'manage_orders.php' ? 'active' : ''; ?>" href="manage_orders.php">
                        <i class="fa-solid fa-truck-ramp-box me-2"></i> Customer Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'manage_users.php' ? 'active' : ''; ?>" href="manage_users.php">
                        <i class="fa-solid fa-users me-2"></i> Users & Staff
                    </a>
                </li>
            </ul>

            <hr class="border-secondary my-4">

            <div class="px-2">
                <a href="../index.php" class="btn btn-outline-light btn-sm w-100 mb-2 rounded-pill">
                    <i class="fa-solid fa-store me-1"></i> View Live Store
                </a>
                <a href="../actions/logout.php" class="btn btn-danger btn-sm w-100 rounded-pill">
                    <i class="fa-solid fa-power-off me-1"></i> Sign Out
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Header bar -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <div>
                    <h2 class="h4 fw-bold mb-1"><?php echo isset($page_heading) ? htmlspecialchars($page_heading) : "Atelier Overview"; ?></h2>
                    <span class="text-muted small">Welcome back, <strong><?php echo htmlspecialchars($admin['name']); ?></strong> (Administrator)</span>
                </div>
                <div>
                    <span class="badge bg-light text-dark border p-2">
                        <i class="fa-regular fa-clock me-1 text-warning"></i> <?php echo date('D, d M Y'); ?>
                    </span>
                </div>
            </div>

            <!-- Flash Alerts -->
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?> alert-dismissible fade show rounded-4" role="alert">
                    <?php echo htmlspecialchars($flash['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
