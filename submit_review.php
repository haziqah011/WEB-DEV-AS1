<?php
// submit_review.php - saves a review for ONE specific pet, then returns to that pet's page.
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pets.php");
    exit;
}

csrf_check();

$petId   = (int)($_POST['pet_id'] ?? 0);
$name    = trim($_POST['name'] ?? '');
$rating  = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

// The pet must exist
$check = $pdo->prepare("SELECT id FROM pets WHERE id = ?");
$check->execute([$petId]);
if (!$check->fetch()) {
    header("Location: pets.php");
    exit;
}

$back = "pet_details.php?id=" . $petId;

if ($name === '' || mb_strlen($name) > 60) {
    header("Location: $back&review=error&code=name#reviews");
    exit;
}
if ($rating < 1 || $rating > 5) {
    header("Location: $back&review=error&code=rating#reviews");
    exit;
}
if ($comment === '' || mb_strlen($comment) > 1000) {
    header("Location: $back&review=error&code=comment#reviews");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO reviews (pet_id, name, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->execute([$petId, $name, $rating, $comment]);

header("Location: $back&review=success#reviews");
exit;
