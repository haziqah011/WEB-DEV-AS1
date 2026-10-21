
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
            <a href="pets.php" class="active">Adopt a Pet</a>
            <a href="about_us.php">About Us</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>


    <!-- AVAILABLE PETS SECTION -->
    <section class="pets-section">

        <h2>Meet Our Lovely Pets!</h2>

        <p>
            Every pet deserves a loving home.
            Find your perfect companion today!
        </p>


        <!-- PET FILTER SECTION -->
        <div class="filter-box">

            <div class="filter-heading">

                <span class="section-label">
                    FIND YOUR PERFECT MATCH
                </span>

                <h3>Find your forever friend.</h3>

            </div>


            <!-- FILTER OPTIONS -->
            <div class="filter-options">

                <!-- PET TYPE -->
                <div class="filter-group">

                    <label for="typeFilter">Pet Type</label>

                    <select id="typeFilter">
                        <option value="all">Any pet</option>
                        <option value="cat">Cat</option>
                        <option value="dog">Dog</option>
                    </select>

                </div>


                <!-- GENDER -->
                <div class="filter-group">

                    <label for="genderFilter">Gender</label>

                    <select id="genderFilter">
                        <option value="all">Any gender</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </select>

                </div>


                
                <!-- BREED -->
                <div class="filter-group">

                    <label for="breedFilter">Breed</label>

                    <select id="breedFilter">
                        <option value="all">Any breed</option>
                        <option value="Domestic Shorthair">Domestic Shorthair</option>
                        <option value="Labrador Retriever">Labrador Retriever</option>
                    </select>

                </div>


                <!-- AGE -->
                <div class="filter-group">

                    <label for="ageFilter">Age</label>

                    <select id="ageFilter">
                        <option value="all">Any age</option>
                        <option value="baby">
                            Baby (Under 1 Year Old)
                        </option>
                        <option value="young">
                            Young (1–2 Years Old)
                        </option>
                        <option value="adult">
                            Adult (3+ Years Old)
                        </option>
                    </select>

                </div>

            </div>


            <!-- FILTER BUTTONS -->
            <div class="filter-actions">

                <button type="button" id="clearFilters">
                    Clear all
                </button>

                <button type="button" id="searchPets">
                    Search Pets →
                </button>

            </div>

        </div>


        <!-- NO RESULTS MESSAGE -->
        <p id="noPetsMessage" hidden>
            No pets match your selected filters.
        </p>


        <!-- PET CARDS -->
        <div class="pets-container">

            <!-- OREO -->
            <div class="pet-card"
                 data-type="cat"
                 data-age="young"
                 data-breed="Domestic Shorthair"
                 data-gender="female">

                <img src="petimages/Oreo.jpg" alt="Oreo">

                <h3>Oreo</h3>

                <p>Cat • 2 Years Old</p>

                <a href="pet_details.php?id=1" class="pet-btn">
                    View Details
                </a>

            </div>


            <!-- MALLOW -->
            <div class="pet-card"
                 data-type="cat"
                 data-age="young"
                 data-breed="Domestic Shorthair"
                 data-gender="female">

                <img src="petimages/Mallow.jpg" alt="Mallow">

                <h3>Mallow</h3>

                <p>Cat • 1 Year Old</p>

                <a href="pet_details.php?id=2" class="pet-btn">
                    View Details
                </a>

            </div>


            <!-- MAX -->
            <div class="pet-card"
                 data-type="dog"
                 data-age="adult"
                 data-breed="Labrador Retriever"
                 data-gender="male">

                <img src="petimages/Max.jpg?v=2" alt="Max">

                <h3>Max</h3>

                <p>Dog • 3 Years Old</p>

                <a href="pet_details.php?id=3" class="pet-btn">
                    View Details
                </a>

            </div>

        </div>

    </section>


    <!-- PET FILTER JAVASCRIPT -->
    <script src="pet_filter.js?v=3"></script>

</body>
</html>
