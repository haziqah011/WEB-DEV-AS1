<?php
// Temporary pet data
$pets = [
    1 => "Oreo",
    2 => "Mallow",
    3 => "Max"
];

// Get selected pet from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || !isset($pets[$id])) {
    http_response_code(404);
    exit("Pet not found.");
}

$petName = $pets[$id];
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
        <a href="pets.php" class="active">Adopt a Pet</a>
        <a href="#">About Us</a>
        <a href="#">Admin</a>
    </nav>
</header>

<section class="adoption-section">

    <h2>Adoption Application</h2>
    <p>Take the first step towards giving a pet a loving home.</p>

    <form class="adoption-form" action="#" method="POST">

        <h3>Pet Information</h3>

        <label>Pet You Want to Adopt</label>
        <input
            type="text"
            value="<?php echo htmlspecialchars($petName); ?>"
            readonly
        >

        <input
            type="hidden"
            name="pet_id"
            value="<?php echo $id; ?>"
        >

        <h3>Personal Information</h3>

        <label for="fullname">Full Name</label>
        <input
            type="text"
            id="fullname"
            name="fullname"
            placeholder="Enter your full name"
            required
        >

        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <label for="phone">Phone Number</label>
        <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="Enter your phone number"
            required
        >

        <label for="address">Home Address</label>
        <textarea
            id="address"
            name="address"
            rows="3"
            placeholder="Enter your home address"
            required
        ></textarea>

        <h3>Adoption Information</h3>

        <label for="housing">Housing Type</label>
        <select id="housing" name="housing" required>
            <option value="">Select housing type</option>
            <option value="House">House</option>
            <option value="Apartment">Apartment</option>
            <option value="Other">Other</option>
        </select>

        <label for="experience">
            Have You Owned Pets Before?
        </label>

        <select id="experience" name="experience" required>
            <option value="">Select an option</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

        <label for="reason">
            Why Do You Want to Adopt This Pet?
        </label>

        <textarea
            id="reason"
            name="reason"
            rows="5"
            placeholder="Tell us your reason for adoption..."
            required
        ></textarea>

        <div class="agreement">
            <input
                type="checkbox"
                id="agree"
                name="agree"
                required
            >
            <label for="agree">
                I confirm that the information provided is accurate.
            </label>
        </div>

        <button type="submit" class="submit-btn">
            Submit Application
        </button>

    </form>

</section>

</body>
</html>
