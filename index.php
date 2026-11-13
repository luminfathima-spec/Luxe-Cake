<?php
$page_title = "Artisan Bakery & Cake Boutique";
require_once __DIR__ . '/includes/header.php';

// Fetch featured cakes
$stmt = $pdo->query("SELECT c.*, cat.name as category_name FROM cakes c JOIN categories cat ON c.category_id = cat.id WHERE c.is_available = 1 AND c.is_featured = 1 ORDER BY c.id ASC LIMIT 6");
$featured_cakes = $stmt->fetchAll();

// Fetch categories
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $cat_stmt->fetchAll();
?>

<!-- Hero Banner -->
<section class="hero-banner text-center d-flex align-items-center">
    <div class="container">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill text-uppercase fw-bold mb-3 letter-spacing-1">
            Artisanal Confections Crafted with Elegance
        </span>
        <h1 class="display-3 fw-bold mb-3">Where Taste Meets Luxury</h1>
        <p class="lead mb-4 mx-auto" style="max-width: 680px; color: #F0EAE1;">
            Indulge in couture cakes created for weddings, milestone celebrations, and unforgettable personal memories. Handcrafted from French butter, Belgian cocoa, and organic cream.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="cakes.php" class="btn btn-luxury px-4 py-3">Order Online</a>
            <a href="#featured" class="btn btn-outline-light px-4 py-3 rounded-pill fw-semibold text-uppercase">Explore Featured</a>
        </div>
    </div>
</section>

<!-- Highlights Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="p-3">
                    <i class="fa-solid fa-cake-candles fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold mb-1">Baked Fresh Daily</h5>
                    <p class="text-muted small mb-0">Never frozen, prepared fresh in our morning bake.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3">
                    <i class="fa-solid fa-gem fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold mb-1">Artisan Ingredients</h5>
                    <p class="text-muted small mb-0">Authentic Belgian chocolate & Madagascar vanilla.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3">
                    <i class="fa-solid fa-truck-fast fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold mb-1">Temperature Chilled</h5>
                    <p class="text-muted small mb-0">Delivered in refrigerated bespoke boxes.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3">
                    <i class="fa-solid fa-pen-fancy fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold mb-1">Custom Piped Message</h5>
                    <p class="text-muted small mb-0">Personalized greeting complimentary with every order.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Bar -->
<section class="py-4">
    <div class="container text-center">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="cakes.php" class="category-pill active">All Creations</a>
            <?php foreach ($categories as $cat): ?>
                <a href="cakes.php?category=<?php echo $cat['id']; ?>" class="category-pill">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Cakes Section -->
<section id="featured" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-warning fw-bold text-uppercase letter-spacing-2 small">Handpicked Favorites</span>
            <h2 class="display-6 fw-bold mt-1">Signature Masterpieces</h2>
            <div class="mx-auto mt-2" style="width: 60px; height: 3px; background: #D4AF37;"></div>
        </div>

        <div class="row g-4">
            <?php foreach ($featured_cakes as $cake): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="cake-card">
                        <div class="cake-card-img-wrap">
                            <span class="cake-badge-featured">Signature</span>
                            <img src="<?php echo htmlspecialchars($cake['image']); ?>" alt="<?php echo htmlspecialchars($cake['name']); ?>">
                        </div>
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <div class="text-muted small mb-1"><?php echo htmlspecialchars($cake['category_name']); ?></div>
                            <h4 class="h5 fw-bold mb-2">
                                <a href="cake_details.php?id=<?php echo $cake['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($cake['name']); ?>
                                </a>
                            </h4>
                            <p class="text-muted small flex-grow-1">
                                <?php echo htmlspecialchars(substr($cake['description'], 0, 110)) . '...'; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <div>
                                    <span class="text-muted small d-block">Starting from</span>
                                    <span class="cake-price">$<?php echo number_format($cake['price'], 2); ?></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="cake_details.php?id=<?php echo $cake['id']; ?>" class="btn btn-outline-luxury">View</a>
                                    <form action="actions/add_to_cart.php" method="POST" class="d-inline">
                                        <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                                        <input type="hidden" name="weight" value="1kg">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-luxury"><i class="fa-solid fa-cart-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="cakes.php" class="btn btn-outline-luxury px-4 py-3">View Complete Collection <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- About Atelier Section -->
<section id="about-us" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="images/cakes/vanilla-rose.svg" alt="Luxe Atelier" class="img-fluid rounded-4 shadow-lg p-3 bg-light">
            </div>
            <div class="col-lg-6">
                <span class="text-warning fw-bold text-uppercase small">Our Heritage</span>
                <h2 class="display-6 fw-bold mb-3">Baking with Uncompromising Artistry</h2>
                <p class="text-muted">
                    At Luxe Cake, we believe every confection tells a story. Established with a philosophy of uncompromising quality, each layer is created from scratch with no artificial preservatives, zero shortenings, and only whole culinary ingredients.
                </p>
                <p class="text-muted">
                    Whether you are commemorating a dream wedding, an intimate birthday dinner, or seeking a quiet moment of afternoon indulgence, our master bakers and decorators ensure every bite is perfection.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-check-circle text-warning fs-5"></i>
                            <span class="fw-semibold">100% Eggless Options</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-check-circle text-warning fs-5"></i>
                            <span class="fw-semibold">Custom Fondant Work</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-check-circle text-warning fs-5"></i>
                            <span class="fw-semibold">Gluten-Free Selections</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-check-circle text-warning fs-5"></i>
                            <span class="fw-semibold">Express Doorstep Dispatch</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
