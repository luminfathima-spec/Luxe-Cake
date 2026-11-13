<?php
$page_title = "Couture Cake Catalog";
require_once __DIR__ . '/includes/header.php';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

$query = "SELECT c.*, cat.name as category_name FROM cakes c JOIN categories cat ON c.category_id = cat.id WHERE c.is_available = 1";
$params = [];

if ($category_id > 0) {
    $query .= " AND c.category_id = ?";
    $params[] = $category_id;
}

if (!empty($search)) {
    $query .= " AND (c.name LIKE ? OR c.description LIKE ? OR c.flavor LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($sort === 'price_asc') {
    $query .= " ORDER BY c.price ASC";
} elseif ($sort === 'price_desc') {
    $query .= " ORDER BY c.price DESC";
} else {
    $query .= " ORDER BY c.is_featured DESC, c.id ASC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$cakes = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
?>

<div class="bg-white py-4 border-bottom">
    <div class="container">
        <h1 class="h2 fw-bold mb-2">Our Artisan Cake Collection</h1>
        <p class="text-muted mb-0">Browse couture celebration cakes, handcrafted wedding tiers, and velvety baked cheesecakes.</p>
    </div>
</div>

<div class="container py-5">
    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-4 shadow-sm mb-5">
        <form method="GET" action="cakes.php" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by cake name, flavor, or chocolate..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>

            <div class="col-md-4">
                <select name="category" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="0">All Categories (<?php echo count($cakes); ?>)</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Sort by: Featured</option>
                    <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Cakes Grid -->
    <?php if (empty($cakes)): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-cake-candles fs-1 text-muted mb-3"></i>
            <h4 class="fw-bold">No cakes found</h4>
            <p class="text-muted">Try adjusting your search criteria or explore all categories.</p>
            <a href="cakes.php" class="btn btn-outline-luxury mt-2">Reset Filters</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cakes as $cake): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="cake-card">
                        <div class="cake-card-img-wrap">
                            <?php if ($cake['is_featured']): ?>
                                <span class="cake-badge-featured">Featured</span>
                            <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($cake['image']); ?>" alt="<?php echo htmlspecialchars($cake['name']); ?>">
                        </div>
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small"><?php echo htmlspecialchars($cake['category_name']); ?></span>
                                <span class="badge bg-light text-dark border small"><?php echo htmlspecialchars($cake['flavor']); ?></span>
                            </div>
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
                                    <span class="text-muted small d-block">Base Price</span>
                                    <span class="cake-price">$<?php echo number_format($cake['price'], 2); ?></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="cake_details.php?id=<?php echo $cake['id']; ?>" class="btn btn-outline-luxury">Customize</a>
                                    <form action="actions/add_to_cart.php" method="POST" class="d-inline">
                                        <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                                        <input type="hidden" name="weight" value="1kg">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-luxury" title="Add to Bag"><i class="fa-solid fa-cart-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
