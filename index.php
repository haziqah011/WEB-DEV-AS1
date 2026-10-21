<?php
require 'db.php';

// ---------- REVIEW FORM HANDLER ----------
$reviewMsg = "";
$reviewOk  = (($_GET['review'] ?? '') === 'success');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    csrf_check();

    $rName    = trim($_POST['name'] ?? '');
    $rRating  = (int)($_POST['rating'] ?? 0);
    $rComment = trim($_POST['comment'] ?? '');

    if ($rName === '' || mb_strlen($rName) > 60) {
        $reviewMsg = "Please enter your name (up to 60 characters).";
    } elseif ($rRating < 1 || $rRating > 5) {
        $reviewMsg = "Please select a star rating.";
    } elseif ($rComment === '' || mb_strlen($rComment) > 1000) {
        $reviewMsg = "Please write a review (up to 1000 characters).";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (pet_id, name, rating, comment) VALUES (NULL, ?, ?, ?)");
        $stmt->execute([$rName, $rRating, $rComment]);
        header("Location: index.php?review=success#review");
        exit;
    }
}

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
    <link rel="stylesheet" href="review.css">
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


    <!-- REVIEW FORM -->
    <section class="review-section" id="review">
        <h2>Share Your Experience 🐾</h2>
        <p class="review-sub">We would love to hear your thoughts about PawHome!</p>

        <?php if ($reviewOk): ?>
            <div class="review-alert review-success">Thank you! Your review has been submitted.</div>
        <?php elseif ($reviewMsg): ?>
            <div class="review-alert review-error"><?= e($reviewMsg) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php#review" class="review-form">
            <?= csrf_field() ?>

            <label for="rname">Your Name</label>
            <input type="text" id="rname" name="name" maxlength="60"
                   placeholder="Enter your name" required
                   value="<?= e($_POST['name'] ?? '') ?>">

            <label>Your Rating</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">★</label>
            </div>
            <small id="rating-text">Select a rating</small>

            <label for="rcomment">Your Review</label>
            <textarea id="rcomment" name="comment" rows="5" maxlength="1000"
                      placeholder="Tell us what you think about PawHome..." required><?= e($_POST['comment'] ?? '') ?></textarea>

            <button type="submit" name="submit_review" id="review-btn" disabled>Submit Review ➤</button>
        </form>
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

<script>
document.querySelectorAll('.star-rating input').forEach(function (r) {
    r.addEventListener('change', function () {
        document.getElementById('rating-text').textContent = r.value + ' out of 5 stars';
        document.getElementById('review-btn').disabled = false;
    });
});
</script>

</body>
</html>
