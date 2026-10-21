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
                Contact Us
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

        <!-- FIRST SET -->
        <div class="pet-slide">
            <img src="petimages/Oreo.jpg" alt="Oreo">
            <h3>Oreo</h3>
            <p>Domestic Shorthair · 2 Years</p>
            <a href="pet-details.php?id=1"></a>
        </div>

        <div class="pet-slide">
            <img src="petimages/Mallow.jpg" alt="Mallow">
            <h3>Mallow</h3>
            <p>Domestic Shorthair · 1 Year</p>
            <a href="pet-details.php?id=2"></a>
        </div>

        <div class="pet-slide">
            <img src="petimages/Max.jpg?v=2" alt="Max">
            <h3>Max</h3>
            <p>Labrador Retriever · 3 Years</p>
            <a href="pet-details.php?id=3"></a>
        </div>

        <!-- DUPLICATE SET FOR INFINITE LOOP -->
        <div class="pet-slide" aria-hidden="true">
            <img src="petimages/Oreo.jpg" alt="">
            <h3>Oreo</h3>
            <p>Domestic Shorthair · 2 Years</p>
            <a href="pet-details.php?id=1" tabindex="-1"></a>
        </div>

        <div class="pet-slide" aria-hidden="true">
            <img src="petimages/Mallow.jpg" alt="">
            <h3>Mallow</h3>
            <p>Domestic Shorthair · 1 Year</p>
            <a href="pet-details.php?id=2" tabindex="-1"></a>
        </div>

        <div class="pet-slide" aria-hidden="true">
            <img src="petimages/Max.jpg?v=2" alt="">
            <h3>Max</h3>
            <p>Labrador Retriever · 3 Years</p>
            <a href="pet-details.php?id=3" tabindex="-1"></a>
        </div>

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
<footer class="home-footer" id="contact">

    <h2>PawHome 🐾</h2>

    <p>
        Helping little paws find forever homes.
    </p>

    <p class="copyright">
        © 2026 PawHome. All rights reserved.
    </p>

</footer>

</body>
</html>

