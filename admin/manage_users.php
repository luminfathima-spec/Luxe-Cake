<?php
$page_title = "Manage Customers & Staff";
$page_heading = "Registered Accounts";
require_once __DIR__ . '/includes/admin_header.php';

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<div class="bg-white p-4 rounded-4 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Verification</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>#<?php echo $u['id']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['phone'] ?? '—'); ?></td>
                        <td>
                            <span class="badge <?php echo $u['role'] === 'admin' ? 'bg-warning text-dark' : 'bg-light text-dark border'; ?> rounded-pill px-3 py-1">
                                <?php echo strtoupper($u['role']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['is_verified']): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?php echo htmlspecialchars($u['created_at'] ?? '—'); ?></td>
                        <td>
                            <?php if ($u['id'] != $admin['id']): ?>
                                <form action="actions/delete_user.php" method="POST" onsubmit="return confirm('Delete this user account?');" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px;" title="Delete User">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="badge bg-secondary">Current Admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
