<?php
$pets = [
    1 => [
        "name" => "Oreo",
        "type" => "Cat",
        "breed" => "Domestic Shorthair",
        "age" => "2 Years Old",
        "gender" => "Female",
        "health" => "Vaccinated",
        "image" => "petimages/Oreo.jpg",
        "description" => "Oreo is a friendly and playful cat who loves spending time with people."
    ],

    2 => [
        "name" => "Mallow",
        "type" => "Cat",
        "breed" => "Domestic Shorthair",
        "age" => "1 Year Old",
        "gender" => "Female",
        "health" => "Vaccinated",
        "image" => "petimages/Mallow.jpg",
        "description" => "Mallow is a gentle and affectionate cat. She enjoys relaxing in cozy places and loves being around people."
    ],

    3 => [
        "name" => "Max",
        "type" => "Dog",
        "breed" => "Labrador Retriever",
        "age" => "3 Years Old",
        "gender" => "Male",
        "health" => "Vaccinated",
        "image" => "petimages/Max.jpg?v=2",
        "description" => "Max is an energetic and curious dog. He enjoys playing, exploring, and spending time with his family."
    ]
];

// Get the pet ID from the URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Check whether the pet exists
if (!$id || !isset($pets[$id])) {
    http_response_code(404);
    echo "Pet not found.";
    exit;
}

$pet = $pets[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($pet['name']); ?> | PawHome
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>PawHome 🐾</h1>

    <nav>
        <a href="index.php">Home</a>
        <a href="pets.php" class="active">Adopt a Pet</a>
        <a href="#">About Us</a>
        <a href="#">Contact</a>
    </nav>
</header>

<section class="details-section">

    <a href="pets.php" class="back-link">
        ← Back to All Pets
    </a>

    <div class="details-card">
        

        <img
            src="<?php echo htmlspecialchars($pet['image']); ?>"
            alt="<?php echo htmlspecialchars($pet['name']); ?>"
            class="details-image"
        >

        <div class="details-info">

            <p><strong>Type:</strong>
                <?php echo htmlspecialchars($pet['type']); ?>
            </p>

            <p><strong>Breed:</strong>
                <?php echo htmlspecialchars($pet['breed']); ?>
            </p>

            <p><strong>Age:</strong>
                <?php echo htmlspecialchars($pet['age']); ?>
            </p>

            <p><strong>Gender:</strong>
                <?php echo htmlspecialchars($pet['gender']); ?>
            </p>

            <p><strong>Health:</strong>
                <?php echo htmlspecialchars($pet['health']); ?>
            </p>

            <h3>About Me</h3>

            <p>
                <?php echo htmlspecialchars($pet['description']); ?>
            </p>

            
            
    <a href="form.php?id=<?php echo $id; ?>" class="pet-btn">
        Apply for Adoption
    </a>


        </div>

    </div>

</section>

</body>
</html>
