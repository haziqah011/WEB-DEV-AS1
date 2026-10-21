<?php
require 'db.php';

$id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pet = false;
if ($id) {
    $stmt = $pdo->prepare('SELECT id, name, type, status FROM pets WHERE id = ?');
    $stmt->execute([$id]);
    $pet = $stmt->fetch();
}
if (!$pet) {
    http_response_code(404);
    exit('Pet not found. <a href="pets.php">Back to all pets</a>');
}

$errors = [];
$old    = ['fullname' => '', 'email' => '', 'phone' => '', 'address' => '', 'housing' => '', 'experience' => '', 'reason' => ''];
$canApply = ($pet['status'] === 'Available');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canApply) {
    foreach ($old as $key => $unused) {
        $old[$key] = trim($_POST[$key] ?? '');
    }

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $errors[] = 'Your session expired. Please submit the form again.';
    }
    if (mb_strlen($old['fullname']) < 2 || mb_strlen($old['fullname']) > 100) {
        $errors[] = 'Enter your full name (2 to 100 characters).';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 100) {
        $errors[] = 'Enter a valid email address.';
    }
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $old['phone'])) {
        $errors[] = 'Enter a valid phone number (7 to 20 digits, spaces or + - ( ) allowed).';
    }
    if ($old['address'] === '' || mb_strlen($old['address']) > 500) {
        $errors[] = 'Enter your home address (up to 500 characters).';
    }
    if (!in_array($old['housing'], ['House', 'Apartment', 'Other'], true)) {
        $errors[] = 'Select your housing type.';
    }
    if (!in_array($old['experience'], ['Yes', 'No'], true)) {
        $errors[] = 'Tell us whether you have owned pets before.';
    }
    if (mb_strlen($old['reason']) < 10 || mb_strlen($old['reason']) > 1000) {
        $errors[] = 'Tell us why you want to adopt (10 to 1000 characters).';
    }
    if (empty($_POST['agree'])) {
        $errors[] = 'Please confirm that your information is accurate.';
    }

    if (!$errors) {
        try {
            // Block duplicate pending applications for the same pet + email
            $dup = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE pet_id = ? AND email = ? AND status = 'Pending'");
            $dup->execute([$pet['id'], $old['email']]);

            if ($dup->fetchColumn() > 0) {
                $errors[] = 'You already have a pending application for ' . $pet['name'] . ' with this email address.';
            } else {
                $ins = $pdo->prepare(
                    'INSERT INTO applications (pet_id, fullname, email, phone, address, housing, experience, reason)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $ins->execute([$pet['id'], $old['fullname'], $old['email'], $old['phone'],
                               $old['address'], $old['housing'], $old['experience'], $old['reason']]);

                $_SESSION['sent_for'] = $pet['id'];
                header('Location: form.php?id=' . (int)$pet['id'] . '&sent=1');
                exit;
            }
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            $errors[] = 'Something went wrong while saving your application. Please try again.';
        }
    }
}

$sent = isset($_GET['sent']) && ($_SESSION['sent_for'] ?? null) === $pet['id'];
if ($sent) { unset($_SESSION['sent_for']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Application | PawHome</title>
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

<section class="adoption-section">

    <h2>Adoption Application</h2>
    <p>Take the first step towards giving a pet a loving home.</p>

<?php if ($sent): ?>
    <div class="adoption-form form-message success" role="status">
        <h3>Thank you!</h3>
        <p>Your application for <strong><?= e($pet['name']) ?></strong> has been received. Our team will review it and contact you by email.</p>
        <a href="pets.php" class="pet-btn">Back to All Pets</a>
    </div>

<?php elseif (!$canApply): ?>
    <div class="adoption-form form-message" role="status">
        <h3><?= e($pet['name']) ?> is not available</h3>
        <p>This pet is currently marked as <strong><?= e($pet['status']) ?></strong>, so applications are closed.</p>
        <a href="pets.php" class="pet-btn">Find another pet</a>
    </div>

<?php else: ?>
    <form class="adoption-form" action="form.php?id=<?= (int)$pet['id'] ?>" method="POST" novalidate>

        <?php if ($errors): ?>
            <div class="form-message error" role="alert">
                <strong>Please fix the following:</strong>
                <ul><?php foreach ($errors as $msg): ?><li><?= e($msg) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <h3>Pet Information</h3>
        <label for="petname">Pet You Want to Adopt</label>
        <input type="text" id="petname" value="<?= e($pet['name']) ?> (<?= e($pet['type']) ?>)" readonly>

        <h3>Personal Information</h3>

        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="fullname" value="<?= e($old['fullname']) ?>" maxlength="100" placeholder="Enter your full name" autocomplete="name" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" maxlength="100" placeholder="Enter your email" autocomplete="email" required>

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" maxlength="20" placeholder="Enter your phone number" autocomplete="tel" required>

        <label for="address">Home Address</label>
        <textarea id="address" name="address" rows="3" maxlength="500" placeholder="Enter your home address" required><?= e($old['address']) ?></textarea>

        <h3>Adoption Information</h3>

        <label for="housing">Housing Type</label>
        <select id="housing" name="housing" required>
            <option value="">Select housing type</option>
            <?php foreach (['House', 'Apartment', 'Other'] as $h): ?>
                <option value="<?= $h ?>" <?= $old['housing'] === $h ? 'selected' : '' ?>><?= $h ?></option>
            <?php endforeach; ?>
        </select>

        <label for="experience">Have You Owned Pets Before?</label>
        <select id="experience" name="experience" required>
            <option value="">Select an option</option>
            <?php foreach (['Yes', 'No'] as $x): ?>
                <option value="<?= $x ?>" <?= $old['experience'] === $x ? 'selected' : '' ?>><?= $x ?></option>
            <?php endforeach; ?>
        </select>

        <label for="reason">Why Do You Want to Adopt <?= e($pet['name']) ?>?</label>
        <textarea id="reason" name="reason" rows="5" maxlength="1000" placeholder="Tell us your reason for adoption..." required><?= e($old['reason']) ?></textarea>

        <div class="agreement">
            <input type="checkbox" id="agree" name="agree" value="1" required>
            <label for="agree">I confirm that the information provided is accurate.</label>
        </div>

        <button type="submit" class="submit-btn">Submit Application</button>
    </form>
<?php endif; ?>

</section>

<script src="form_validate.js"></script>
</body>
</html>
