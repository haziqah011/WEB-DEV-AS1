<?php
require 'db.php';
// Newest pets that are still available
$featured = $pdo->query("SELECT id, name, breed, age, image FROM pets WHERE status = 'Available' ORDER BY id DESC LIMIT 6")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PawHome | Find Your Forever Friend</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- NAVIGATION -->
<header>
    <h1>PawHome 🐾</h1>

    <nav>
        <a href="index.php" class="active">Home</a>
        <a href="pets.php">Adopt a Pet</a>
        <a href="about_us.php">About Us</a>
        <a href="admin.php">Admin</a>
    </nav>
</header>

<main class="home-page">

    <!-- HERO SECTION -->
    <section class="home-hero">

        <div class="hero-content">

            <span class="hero-label">
                EVERY PAW DESERVES A HOME
            </span>

            <h1>
                Find your new
                <span>best friend.</span>
            </h1>

            <p>
                Every pet deserves a second chance
                at happiness. Meet our lovely
                companions and find a furry friend
                to welcome into your family.
            </p>

            
        <!-- HERO BUTTONS -->
        <div class="hero-buttons">

            <a href="pets.php" class="home-button">
                Meet Our Pets →
            </a>

            <a href="contact.php" class="home-button contact-button">
                Contact Us ☎
            </a>

        </div>
        </div>

        <div class="hero-image">
            <img src="petimages/Cat_Dog.jpg"
                 alt="Meet our adorable pets">

            <div class="hero-tag">
                🐾 Your forever friend awaits!
            </div>
        </div>

    </section>


    <!-- FEATURED PETS -->
    <section class="home-featured">

        <h2>Meet our little friends.</h2>

        
<div class="pet-carousel">
    <div class="pet-track">
        <?php if ($featured): ?>
        <?php foreach ([false, true] as $isCopy): /* second pass = duplicate set for the infinite loop */ ?>
            <?php foreach ($featured as $p): ?>
            <div class="pet-slide"<?= $isCopy ? ' aria-hidden="true"' : '' ?>>
                <img src="<?= e(pet_image($p['image'])) ?>" alt="<?= $isCopy ? '' : e($p['name']) ?>">
                <h3><?= e($p['name']) ?></h3>
                <p><?= e($p['breed']) ?> · <?= e($p['age']) ?></p>
                <a class="slide-link" href="pet_details.php?id=<?= (int)$p['id'] ?>"<?= $isCopy ? ' tabindex="-1"' : '' ?>>Meet <?= e($p['name']) ?></a>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php else: ?>
            <p>New pets are coming soon. Please check back later.</p>
        <?php endif; ?>
    </div>
</div>


        <a href="pets.php" class="home-button">
            View All Pets →
        </a>

    </section>


    <!-- ADOPTION PROCESS -->
    <section class="home-process">

        <h2>How It Works</h2>

        <div class="home-steps">

            <div class="home-step">
                <span>01</span>
                <div class="step-icon">🐾</div>
                <h3>Find Your Friend</h3>
                <p>
                    Browse our available pets
                    and find your perfect match.
                </p>
            </div>

            <div class="home-step">
                <span>02</span>
                <div class="step-icon">💌</div>
                <h3>Apply for Adoption</h3>
                <p>
                    Complete a simple adoption
                    application form.
                </p>
            </div>

            <div class="home-step">
                <span>03</span>
                <div class="step-icon">🏡</div>
                <h3>Welcome Them Home</h3>
                <p>
                    Wait for approval and begin
                    your journey together.
                </p>
            </div>

        </div>

    </section>

</main>


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

