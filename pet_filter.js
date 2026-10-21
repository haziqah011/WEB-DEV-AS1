// Filtering is done in PHP + SQL (pets.php). This script only makes the page
// friendlier: the list updates as soon as a dropdown or the checkbox changes.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form.filter-box");
    if (!form) return;

    form.querySelectorAll("select, input[type='checkbox']").forEach(function (field) {
        field.addEventListener("change", function () {
            form.submit();
        });
    });
});
