<?php
//CREATE a new pet or UPDATE an existing one
require_once "admin_auth.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pet = [
    'name' => '', 'type' => 'Cat', 'breed' => '', 'age' => '',
    'gender' => 'Female', 'health' => '', 'image' => 'petimages/',
    'description' => '', 'status' => 'Available'
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        http_response_code(404);
        exit("Pet not found.");
    }
    $pet = $found;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $fields = ['name','type','breed','age','gender','health','image','description','status'];
    foreach ($fields as $f) {
        $pet[$f] = trim($_POST[$f] ?? '');
    }

    foreach (['name','type','breed','age','gender','health','image','description'] as $f) {
        if ($pet[$f] === '') $errors[] = ucfirst($f) . " is required.";
    }
    if (!in_array($pet['gender'], ['Male','Female'], true))                 $errors[] = "Invalid gender.";
    if (!in_array($pet['status'], ['Available','Adopted','Pending'], true)) $errors[] = "Invalid status.";

    if (!$errors) {
        if ($id) {
            $sql = "UPDATE pets SET name=?, type=?, breed=?, age=?, gender=?, health=?,
                    image=?, description=?, status=? WHERE id=?";
            $pdo->prepare($sql)->execute([
                $pet['name'], $pet['type'], $pet['breed'], $pet['age'], $pet['gender'],
                $pet['health'], $pet['image'], $pet['description'], $pet['status'], $id
            ]);
        } else {
            $sql = "INSERT INTO pets (name, type, breed, age, gender, health, image, description, status)
                    VALUES (?,?,?,?,?,?,?,?,?)";
            $pdo->prepare($sql)->execute([
                $pet['name'], $pet['type'], $pet['breed'], $pet['age'], $pet['gender'],
                $pet['health'], $pet['image'], $pet['description'], $pet['status']
            ]);
        }
        header("Location: admin_pets.php?saved=1");
        exit;
    }
}

$pageTitle  = $id ? "Edit Pet" : "Add Pet";
$activePage = "pets";
include "admin_header.php";
?>

<a href="admin_pets.php" class="back-link">← Back to Pets</a>
<h2><?php echo $id ? "Edit Pet" : "Add New Pet"; ?></h2>

<?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><?php echo e($err); ?></div>
<?php endforeach; ?>

<form method="POST" class="adoption-form">
    <?php echo csrf_field(); ?>

    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?php echo e($pet['name']); ?>" required>

    <label for="type">Type (Cat, Dog, Rabbit...)</label>
    <input type="text" id="type" name="type" value="<?php echo e($pet['type']); ?>" required>

    <label for="breed">Breed</label>
    <input type="text" id="breed" name="breed" value="<?php echo e($pet['breed']); ?>" required>

    <label for="age">Age (e.g. 2 Years Old or 8 Months Old)</label>
    <input type="text" id="age" name="age" value="<?php echo e($pet['age']); ?>" required>

    <label for="gender">Gender</label>
    <select id="gender" name="gender">
        <option <?php echo $pet['gender']==='Female'?'selected':''; ?>>Female</option>
        <option <?php echo $pet['gender']==='Male'?'selected':''; ?>>Male</option>
    </select>

    <label for="health">Health</label>
    <input type="text" id="health" name="health" value="<?php echo e($pet['health']); ?>" required>

    <label for="image">Image path (e.g. petimages/Oreo.jpg)</label>
    <input type="text" id="image" name="image" value="<?php echo e($pet['image']); ?>" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4" required><?php echo e($pet['description']); ?></textarea>

    <label for="status">Status</label>
    <select id="status" name="status">
        <?php foreach (['Available','Pending','Adopted'] as $s): ?>
            <option <?php echo $pet['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="submit-btn"><?php echo $id ? "Update Pet" : "Add Pet"; ?></button>
</form>

<?php include "admin_footer.php"; ?>
