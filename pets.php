<?php
require 'db.php';

/* ---------- 1. Read and validate the search / filter inputs ---------- */
$q          = mb_substr(trim($_GET['q'] ?? ''), 0, 50);
$onlyAvail  = isset($_GET['available']);

// Categories (pet types) come from the database, so new types appear automatically
$categories = $pdo->query("SELECT type, COUNT(*) AS total FROM pets GROUP BY type ORDER BY type")->fetchAll();
$typeNames  = array_column($categories, 'type');
$totalPets  = array_sum(array_column($categories, 'total'));

$type = in_array($_GET['type'] ?? '', $typeNames, true) ? $_GET['type'] : '';

$breeds = $pdo->query("SELECT DISTINCT breed FROM pets ORDER BY breed")->fetchAll(PDO::FETCH_COLUMN);
$breed  = in_array($_GET['breed'] ?? '', $breeds, true) ? $_GET['breed'] : '';

$genders = ['Female', 'Male'];
$gender  = in_array($_GET['gender'] ?? '', $genders, true) ? $_GET['gender'] : '';

// Ages are stored as text 
$ageGroups = [
    'baby'  => ["Baby (Under 1 Year Old)", "age LIKE '%Month%'"],
    'young' => ["Young (1–2 Years Old)",   "age REGEXP '^[12] Year'"],
    'adult' => ["Adult (3+ Years Old)",    "age REGEXP '^([3-9]|[1-9][0-9]+) Year'"],
];
$age = array_key_exists($_GET['age'] ?? '', $ageGroups) ? $_GET['age'] : '';

/* ---------- 2. Build the query  ---------- */
$where = [];
$args  = [];
if ($q !== '')   { $where[] = '(name LIKE ? OR breed LIKE ? OR description LIKE ?)'; array_push($args, "%$q%", "%$q%", "%$q%"); }
if ($type)       { $where[] = 'type = ?';   $args[] = $type; }
if ($breed)      { $where[] = 'breed = ?';  $args[] = $breed; }
if ($gender)     { $where[] = 'gender = ?'; $args[] = $gender; }
if ($age)        { $where[] = $ageGroups[$age][1]; }          // fixed strings from the list above
if ($onlyAvail)  { $where[] = "status = 'Available'"; }

$sql = 'SELECT id, name, type, breed, age, gender, description, image, status FROM pets'
     . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
     . " ORDER BY (status = 'Available') DESC, name";
$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$pets = $stmt->fetchAll();

function options(array $list, string $selected): string {
    $html = '';
    foreach ($list as $item) {
        $html .= '<option value="' . e($item) . '"' . ($item === $selected ? ' selected' : '') . '>' . e($item) . '</option>';
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt a Pet | PawHome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVIGATION BAR -->
<header>
    <h1>PawHome 🐾</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="pets.php" class="active" aria-current="page">Adopt a Pet</a>
        <a href="about_us.php">About Us</a>
        <a href="admin.php">Admin</a>
    </nav>
</header>

<section class="pets-section">

    <h2>Meet Our Lovely Pets!</h2>
    <p>Every pet deserves a loving home. Find your perfect companion today!</p>

    <!-- PET CATEGORIES -->
    <div class="category-chips" aria-label="Pet categories">
        <a href="pets.php" class="<?= $type === '' ? 'active' : '' ?>">All pets (<?= (int)$totalPets ?>)</a>
        <?php foreach ($categories as $c): ?>
            <a href="pets.php?type=<?= urlencode($c['type']) ?>" class="<?= $type === $c['type'] ? 'active' : '' ?>">
                <?= e($c['type']) ?>s (<?= (int)$c['total'] ?>)
            </a>
        <?php endforeach; ?>
    </div>

    <!-- SEARCH + FILTERS -->
    <form class="filter-box" method="get" action="pets.php">

        <div class="filter-heading">
            <span class="section-label">FIND YOUR PERFECT MATCH</span>
            <h3>Find your forever friend.</h3>
        </div>

        <div class="filter-options">

            <div class="filter-group filter-wide">
                <label for="q">Search by name, breed or keyword</label>
                <input type="search" id="q" name="q" value="<?= e($q) ?>" maxlength="50" placeholder="e.g. Oreo, Labrador, playful">
            </div>

            <div class="filter-group">
                <label for="typeFilter">Pet Type</label>
                <select id="typeFilter" name="type">
                    <option value="">Any pet</option>
                    <?= options($typeNames, $type) ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="genderFilter">Gender</label>
                <select id="genderFilter" name="gender">
                    <option value="">Any gender</option>
                    <?= options($genders, $gender) ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="breedFilter">Breed</label>
                <select id="breedFilter" name="breed">
                    <option value="">Any breed</option>
                    <?= options($breeds, $breed) ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="ageFilter">Age</label>
                <select id="ageFilter" name="age">
                    <option value="">Any age</option>
                    <?php foreach ($ageGroups as $key => [$label]): ?>
                        <option value="<?= $key ?>" <?= $age === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-check">
                <input type="checkbox" id="availableOnly" name="available" value="1" <?= $onlyAvail ? 'checked' : '' ?>>
                <label for="availableOnly">Show only pets that are available</label>
            </div>
        </div>

        <div class="filter-actions">
            <a href="pets.php" id="clearFilters">Clear all</a>
            <button type="submit" id="searchPets">Search Pets →</button>
        </div>
    </form>

    <p class="results-count" aria-live="polite">
        <?= count($pets) ?> pet<?= count($pets) === 1 ? '' : 's' ?> found
    </p>

    <?php if (!$pets): ?>
        <p id="noPetsMessage">No pets match your search. Try removing a filter or <a href="pets.php">clear all filters</a>.</p>
    <?php else: ?>
    <!-- PET LISTINGS -->
    <div class="pets-container">
        <?php foreach ($pets as $p): ?>
            <div class="pet-card">
                <div class="pet-image">
                    <img src="<?= e(pet_image($p['image'])) ?>" alt="<?= e($p['name']) ?>, a <?= e(strtolower($p['type'])) ?>" loading="lazy">
                    <span class="status-badge <?= e(status_class($p['status'])) ?>"><?= e($p['status']) ?></span>
                </div>

                <h3><?= e($p['name']) ?></h3>
                <p><?= e($p['breed']) ?> • <?= e($p['age']) ?></p>

                <a href="pet_details.php?id=<?= (int)$p['id'] ?>" class="pet-btn">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</section>

<script src="pet_filter.js?v=4"></script>


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