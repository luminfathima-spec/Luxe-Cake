<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
$flash = get_flash();
$cart_num = cart_count();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " | Luxe Cake" : "Luxe Cake — Artisan Online Cake Ordering"; ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header Navigation -->
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-luxury">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.svg" alt="Luxe Cake Logo">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#luxeNavbar" aria-controls="luxeNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars-staggered text-dark fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="luxeNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cakes.php">Our Cakes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cakes.php?category=1">Truffles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cakes.php?category=2">Cheesecakes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about-us">About</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <!-- Cart -->
                    <a href="cart.php" class="btn btn-outline-secondary position-relative rounded-pill px-3 py-2 border-0 bg-light">
                        <i class="fa-solid fa-bag-shopping text-dark fs-5"></i>
                        <?php if ($cart_num > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_num; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Account / Admin -->
                    <?php if ($user): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-luxury dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user me-1"></i> <?php echo htmlspecialchars(explode(' ', $user['name'])[0]); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                                <?php if (is_admin()): ?>
                                    <li><a class="dropdown-item py-2 fw-semibold text-primary" href="admin/index.php"><i class="fa-solid fa-gauge-high me-2"></i>Admin Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item py-2" href="my_orders.php"><i class="fa-solid fa-receipt me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="actions/logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-luxury">Login</a>
                        <a href="register.php" class="btn btn-luxury d-none d-md-inline-block">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <!-- Flash Messages -->
    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?> alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                <i class="fa-solid <?php echo $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?> me-2"></i>
                <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
