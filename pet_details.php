<?php
require 'db.php';

$id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pet = false;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM pets WHERE id = ?');
    $stmt->execute([$id]);
    $pet = $stmt->fetch();
}
if (!$pet) {
    http_response_code(404);
}

// Other pets of the same type
$similar = [];
if ($pet) {
    $s = $pdo->prepare('SELECT id, name, age, image, status FROM pets WHERE type = ? AND id <> ? ORDER BY (status = \'Available\') DESC, name LIMIT 3');
    $s->execute([$pet['type'], $pet['id']]);
    $similar = $s->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pet ? e($pet['name']) : 'Pet not found' ?> | PawHome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>PawHome 🐾</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="pets.php" class="active" aria-current="page">Adopt a Pet</a>
        <a href="about_us.php">About Us</a>
        <a href="admin.php">Admin</a>
    </nav>
</header>

<section class="details-section">

    <a href="pets.php" class="back-link">← Back to All Pets</a>

<?php if (!$pet): ?>
    <div class="details-card">
        <div class="details-info">
            <h2>Pet not found</h2>
            <p>This pet may have been adopted or removed. <a href="pets.php">Browse all pets</a>.</p>
        </div>
    </div>
<?php else: ?>
    <div class="details-card">

        <img src="<?= e(pet_image($pet['image'])) ?>" alt="<?= e($pet['name']) ?>" class="details-image">

        <div class="details-info">
            <h2>
                <?= e($pet['name']) ?>
                <span class="status-badge inline <?= e(status_class($pet['status'])) ?>"><?= e($pet['status']) ?></span>
            </h2>

            <p><strong>Type:</strong> <?= e($pet['type']) ?></p>
            <p><strong>Breed:</strong> <?= e($pet['breed']) ?></p>
            <p><strong>Age:</strong> <?= e($pet['age']) ?></p>
            <p><strong>Gender:</strong> <?= e($pet['gender']) ?></p>
            <p><strong>Health:</strong> <?= e($pet['health']) ?></p>
            <p><strong>Adoption status:</strong> <?= e($pet['status']) ?></p>

            <h3>About Me</h3>
            <p><?= nl2br(e($pet['description'])) ?></p>

            <?php if ($pet['status'] === 'Available'): ?>
                <a href="form.php?id=<?= (int)$pet['id'] ?>" class="pet-btn">Apply for Adoption</a>
            <?php else: ?>
                <p class="status-note"><?= e($pet['name']) ?> is no longer available for adoption. Please look at our other pets below or on the <a href="pets.php">Adopt a Pet</a> page.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($similar): ?>
    <div class="similar-pets">
        <h3>More <?= e(strtolower($pet['type'])) ?>s you might like</h3>
        <div class="similar-grid">
            <?php foreach ($similar as $sp): ?>
                <a href="pet_details.php?id=<?= (int)$sp['id'] ?>" class="similar-card">
                    <img src="<?= e(pet_image($sp['image'])) ?>" alt="<?= e($sp['name']) ?>" loading="lazy">
                    <strong><?= e($sp['name']) ?></strong>
                    <span><?= e($sp['age']) ?> • <?= e($sp['status']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

</section>


<!-- FOOTER -->

<footer class="home-footer">
    <div class="footer-container">

        <!-- PawHome -->
        <div class="footer-brand">
            <h2>PawHome 🐾</h2>
            <p>Helping little paws find forever homes.</p>
        </div>

        <!-- Explore -->
        <div class="footer-section">
            <h3>Explore</h3>
            <a href="index.php">Home</a>
            <a href="about_us.php">About Us</a>
            <a href="pets.php">Pet Adoption</a>
        </div>

        <!-- Get in Touch -->
        <div class="footer-section">
            <h3>Get in Touch</h3>
            <a href="contact.php">Contact Us</a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 PawHome. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
