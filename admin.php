<?php
// admin.php - administrator login page
require_once "db.php";

// Already logged in? Go straight to dashboard.
if (!empty($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];
        header("Location: admin_dashboard.php");
        exit;
    }
    $error = "Incorrect username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | PawHome</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header>
    <h1>PawHome 🐾</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="pets.php">Adopt a Pet</a>
        <a href="about_us.php">About Us</a>
        <a href="admin.php" class="active">Admin</a>
    </nav>
</header>

<main class="admin-page">
    <div class="login-box">
        <h2>Admin Login</h2>
        <p>Sign in to manage pets and applications.</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="adoption-form">
            <?php echo csrf_field(); ?>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
</main>

</body>
</html>
