<?php
// pet_reviews.php - include this inside pet_details.php (after db.php has been loaded).
// Shows the reviews for the pet in ?id=... and a form to add a new one.

$revPetId = (int)($_GET['id'] ?? 0);

$revStmt = $pdo->prepare("SELECT name, rating, comment, created_at FROM reviews WHERE pet_id = ? ORDER BY created_at DESC");
$revStmt->execute([$revPetId]);
$petReviews = $revStmt->fetchAll();

$revCount = count($petReviews);
$revAvg   = $revCount ? array_sum(array_column($petReviews, 'rating')) / $revCount : 0;

$revStatus = $_GET['review'] ?? '';
$revCode   = $_GET['code'] ?? '';
$revErrors = [
    'name'    => 'Please enter your name (up to 60 characters).',
    'rating'  => 'Please select a star rating.',
    'comment' => 'Please write a review (up to 1000 characters).',
];
?>
<link rel="stylesheet" href="review.css">

<section class="review-section" id="reviews">
    <h2>Reviews</h2>

    <?php if ($revCount): ?>
        <p class="review-sub">
            <span class="stars-shown"><?= str_repeat('★', (int)round($revAvg)) . str_repeat('☆', 5 - (int)round($revAvg)) ?></span>
            <?= number_format($revAvg, 1) ?> out of 5 (<?= $revCount ?> review<?= $revCount === 1 ? '' : 's' ?>)
        </p>
    <?php else: ?>
        <p class="review-sub">No reviews yet. Be the first to share your thoughts!</p>
    <?php endif; ?>

    <!-- EXISTING REVIEWS -->
    <div class="review-list">
        <?php foreach ($petReviews as $r): ?>
            <div class="review-card">
                <div class="review-card-top">
                    <strong><?= e($r['name']) ?></strong>
                    <span class="stars-shown"><?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?></span>
                </div>
                <p><?= nl2br(e($r['comment'])) ?></p>
                <small><?= e(date('d M Y', strtotime($r['created_at']))) ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ADD A REVIEW -->
    <h3 class="review-form-title">Leave a review</h3>

    <?php if ($revStatus === 'success'): ?>
        <div class="review-alert review-success">Thank you! Your review has been submitted.</div>
    <?php elseif ($revStatus === 'error' && isset($revErrors[$revCode])): ?>
        <div class="review-alert review-error"><?= e($revErrors[$revCode]) ?></div>
    <?php endif; ?>

    <form method="POST" action="submit_review.php" class="review-form">
        <?= csrf_field() ?>
        <input type="hidden" name="pet_id" value="<?= $revPetId ?>">

        <label for="prname">Your Name</label>
        <input type="text" id="prname" name="name" maxlength="60" placeholder="Enter your name" required>

        <label>Your Rating</label>
        <div class="star-rating">
            <input type="radio" id="prstar5" name="rating" value="5"><label for="prstar5" title="5 stars">★</label>
            <input type="radio" id="prstar4" name="rating" value="4"><label for="prstar4" title="4 stars">★</label>
            <input type="radio" id="prstar3" name="rating" value="3"><label for="prstar3" title="3 stars">★</label>
            <input type="radio" id="prstar2" name="rating" value="2"><label for="prstar2" title="2 stars">★</label>
            <input type="radio" id="prstar1" name="rating" value="1"><label for="prstar1" title="1 star">★</label>
        </div>
        <small id="pr-rating-text">Select a rating</small>

        <label for="prcomment">Your Review</label>
        <textarea id="prcomment" name="comment" rows="4" maxlength="1000"
                  placeholder="Tell us about this pet..." required></textarea>

        <button type="submit" id="pr-btn" disabled>Submit Review ➤</button>
    </form>
</section>

<script>
document.querySelectorAll('#reviews .star-rating input').forEach(function (r) {
    r.addEventListener('change', function () {
        document.getElementById('pr-rating-text').textContent = r.value + ' out of 5 stars';
        document.getElementById('pr-btn').disabled = false;
    });
});
</script>
