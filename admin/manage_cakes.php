<?php
$page_title = "Manage Cake Products";
$page_heading = "Artisan Cake Inventory";
require_once __DIR__ . '/includes/admin_header.php';

$cakes = $pdo->query("SELECT c.*, cat.name as category_name FROM cakes c JOIN categories cat ON c.category_id = cat.id ORDER BY c.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Manage recipe profiles, pricing tiers, and presentation images.</p>
    </div>
    <button class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCakeModal">
        <i class="fa-solid fa-plus me-1"></i> Add New Cake
    </button>
</div>

<div class="bg-white p-4 rounded-4 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Cake</th>
                    <th>Category</th>
                    <th>Flavor</th>
                    <th>Base Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cakes as $cake): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="../<?php echo htmlspecialchars($cake['image']); ?>" class="rounded-3" style="width: 55px; height: 55px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($cake['name']); ?></h6>
                                    <span class="text-muted small"><?php echo htmlspecialchars(substr($cake['description'], 0, 60)) . '...'; ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($cake['category_name']); ?></span></td>
                        <td><?php echo htmlspecialchars($cake['flavor']); ?></td>
                        <td class="fw-bold text-dark">$<?php echo number_format($cake['price'], 2); ?></td>
                        <td>
                            <?php if ($cake['is_available']): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">In Stock</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">Sold Out</span>
                            <?php endif; ?>
                            <?php if ($cake['is_featured']): ?>
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 34px; height: 34px;" data-bs-toggle="modal" data-bs-target="#editCakeModal<?php echo $cake['id']; ?>" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="actions/delete_cake.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this cake?');" class="d-inline">
                                    <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 34px; height: 34px;" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Cake Modal -->
                    <div class="modal fade" id="editCakeModal<?php echo $cake['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Edit <?php echo htmlspecialchars($cake['name']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="actions/edit_cake.php" method="POST">
                                    <input type="hidden" name="cake_id" value="<?php echo $cake['id']; ?>">
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold">Cake Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($cake['name']); ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Category</label>
                                                <select name="category_id" class="form-select" required>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $cake['category_id'] ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($cat['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Flavor Notes</label>
                                                <input type="text" name="flavor" class="form-control" value="<?php echo htmlspecialchars($cake['flavor']); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Base Price ($)</label>
                                                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $cake['price']; ?>" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Weight Options</label>
                                                <input type="text" name="weight_options" class="form-control" value="<?php echo htmlspecialchars($cake['weight_options']); ?>">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Image Path</label>
                                                <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($cake['image']); ?>" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Description</label>
                                                <textarea name="description" rows="3" class="form-control" required><?php echo htmlspecialchars($cake['description']); ?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat_<?php echo $cake['id']; ?>" <?php echo $cake['is_featured'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label fw-semibold" for="feat_<?php echo $cake['id']; ?>">Feature on Homepage</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" name="is_available" value="1" id="avail_<?php echo $cake['id']; ?>" <?php echo $cake['is_available'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label fw-semibold" for="avail_<?php echo $cake['id']; ?>">Available in Stock</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning text-dark fw-bold">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Cake Modal -->
<div class="modal fade" id="addCakeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Create New Artisan Cake</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/add_cake.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Cake Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Pistachio Raspberry Mousse" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Flavor Notes</label>
                            <input type="text" name="flavor" class="form-control" placeholder="e.g. Sicilian Pistachio & Fresh Berry" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Base Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="48.00" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Weight Options</label>
                            <input type="text" name="weight_options" class="form-control" value="0.5kg, 1kg, 2kg, 3kg">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Image Path</label>
                            <input type="text" name="image" class="form-control" value="images/cakes/chocolate-truffle.svg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Describe the sponge, ganache, texture and inspiration..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="newFeat">
                                <label class="form-check-label fw-semibold" for="newFeat">Feature on Homepage</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="newAvail" checked>
                                <label class="form-check-label fw-semibold" for="newAvail">Available in Stock</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Add Cake</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
