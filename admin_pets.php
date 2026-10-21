<?php
// admin_pets.php - VIEW all pets + DELETE a pet
require_once "admin_auth.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    csrf_check();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Pet deleted.";
    }
}

if (isset($_GET['saved'])) {
    $msg = "Pet saved successfully.";
}

$pets = $pdo->query("SELECT * FROM pets ORDER BY id")->fetchAll();

$pageTitle  = "Manage Pets";
$activePage = "pets";
include "admin_header.php";
?>

<div class="admin-top">
    <h2>Manage Pets</h2>
    <a href="admin_pet_form.php" class="pet-btn">+ Add New Pet</a>
</div>

<?php if ($msg): ?><div class="alert alert-success"><?php echo e($msg); ?></div><?php endif; ?>

<div class="table-wrap">
<table class="admin-table">
    <tr>
        <th>ID</th><th>Photo</th><th>Name</th><th>Type</th><th>Breed</th>
        <th>Age</th><th>Gender</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($pets as $p): ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><img src="<?php echo e($p['image']); ?>" alt="" class="thumb"></td>
        <td><?php echo e($p['name']); ?></td>
        <td><?php echo e($p['type']); ?></td>
        <td><?php echo e($p['breed']); ?></td>
        <td><?php echo e($p['age']); ?></td>
        <td><?php echo e($p['gender']); ?></td>
        <td><span class="badge badge-<?php echo strtolower(e($p['status'])); ?>"><?php echo e($p['status']); ?></span></td>
        <td class="actions">
            <a href="admin_pet_form.php?id=<?php echo $p['id']; ?>" class="btn-small">Edit</a>
            <form method="POST" onsubmit="return confirm('Delete <?php echo e($p['name']); ?>? Their applications and reviews will also be deleted.');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                <button type="submit" class="btn-small btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include "admin_footer.php"; ?>
