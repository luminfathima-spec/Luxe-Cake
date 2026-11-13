<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT c.*, cat.name as category_name FROM cakes c JOIN categories cat ON c.category_id = cat.id WHERE c.id = ?");
$stmt->execute([$id]);
$cake = $stmt->fetch();

if (!$cake) {
    set_flash('danger', 'Cake not found.');
    header('Location: cakes.php');
    exit;
}

$page_title = $cake['name'];
require_once __DIR__ . '/includes/header.php';

$weights = array_map('trim', explode(',', $cake['weight_options']));
?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="cakes.php" class="text-decoration-none text-muted">Cakes</a></li>
            <li class="breadcrumb-item active text-dark" aria-current="page"><?php echo htmlspecialchars($cake['name']); ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Cake Media -->
        <div class="col-lg-6">
            <div class="p-4 bg-white rounded-4 shadow-sm text-center">
                <img src="<?php echo htmlspecialchars($cake['image']); ?>" alt="<?php echo htmlspecialchars($cake['name']); ?>" class="img-fluid rounded-4" style="max-height: 440px;">
            </div>
            <div class="row g-3 mt-2 text-center text-muted small">
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <i class="fa-solid fa-seedling text-success mb-1"></i>
                        <div>Vegetarian Friendly</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <i class="fa-solid fa-snowflake text-info mb-1"></i>
                        <div>Chilled Delivery</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <i class="fa-solid fa-box-open text-warning mb-1"></i>
                        <div>Gift Box Included</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customization & Purchase Options -->
        <div class="col-lg-6">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase mb-2">
                <?php echo htmlspecialchars($cake['category_name']); ?>
            </span>
            <h1 class="display-6 fw-bold mb-2"><?php echo htmlspecialchars($cake['name']); ?></h1>
            <p class="text-muted mb-3"><i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i>Flavor Profile: <strong><?php echo htmlspecialchars($cake['flavor']); ?></strong></p>

            <div class="mb-4">
                <span class="text-muted small d-block">Price:</span>
                <span class="display-6 fw-bold text-warning" id="displayPrice">$<?php echo number_format($cake['price'], 2); ?></span>
            </div>

            <p class="text-secondary mb-4 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($cake['description'])); ?>
            </p>

            <form action="actions/add_to_cart.php" method="POST" class="bg-white p-4 rounded-4 shadow-sm border">
                <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                <input type="hidden" id="basePrice" value="<?php echo $cake['price']; ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Select Cake Size / Weight</label>
                    <select name="weight" id="cakeWeight" class="form-select form-select-lg rounded-3">
                        <?php foreach ($weights as $w): ?>
                            <option value="<?php echo htmlspecialchars($w); ?>" <?php echo $w === '1kg' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($w); ?> (Serves <?php echo $w === '0.5kg' ? '4-5' : ($w === '1kg' ? '8-10' : ($w === '2kg' ? '16-20' : '25+')); ?> guests)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Custom Piped Message on Cake (Free)</label>
                    <input type="text" name="custom_message" class="form-control rounded-3" maxlength="60" placeholder="e.g. Happy Birthday Sarah! 🎉">
                    <div class="form-text small">Maximum 60 characters piping space.</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <label class="form-label fw-bold small text-uppercase">Quantity</label>
                        <input type="number" name="quantity" class="form-control form-control-lg text-center rounded-3" value="1" min="1" max="10">
                    </div>
                    <div class="col-8 d-flex align-items-end">
                        <button type="submit" class="btn btn-luxury btn-lg w-100 rounded-3">
                            <i class="fa-solid fa-cart-plus me-2"></i> Add to Bag
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
