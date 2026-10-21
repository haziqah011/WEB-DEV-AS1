
document.addEventListener("DOMContentLoaded", function () {

    const typeFilter = document.getElementById("typeFilter");
    const genderFilter = document.getElementById("genderFilter");
    const breedFilter = document.getElementById("breedFilter");
    const ageFilter = document.getElementById("ageFilter");

    const searchButton = document.getElementById("searchPets");
    const clearButton = document.getElementById("clearFilters");

    const petCards = document.querySelectorAll(".pet-card");
    const noPetsMessage = document.getElementById("noPetsMessage");

    function filterPets() {

        const selectedType = typeFilter.value;
        const selectedGender = genderFilter.value;
        const selectedBreed = breedFilter.value;
        const selectedAge = ageFilter.value;

        let visibleCount = 0;

        petCards.forEach(function (pet) {

            const matchType =
                selectedType === "all" ||
                pet.dataset.type === selectedType;

            const matchGender =
                selectedGender === "all" ||
                pet.dataset.gender === selectedGender;

            const matchBreed =
                selectedBreed === "all" ||
                pet.dataset.breed === selectedBreed;

            const matchAge =
                selectedAge === "all" ||
                pet.dataset.age === selectedAge;

            const matches =
                matchType &&
                matchGender &&
                matchBreed &&
                matchAge;

            pet.hidden = !matches;

            if (matches) {
                visibleCount++;
            }

        });

        noPetsMessage.hidden = visibleCount > 0;
    }

    // SEARCH PETS
    searchButton.addEventListener("click", filterPets);

    // CLEAR ALL
    clearButton.addEventListener("click", function () {

        typeFilter.value = "all";
        genderFilter.value = "all";
        breedFilter.value = "all";
        ageFilter.value = "all";

        filterPets();

    });

});
