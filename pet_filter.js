document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form.filter-box");
    if (!form) return;

    form.querySelectorAll("select, input[type='checkbox']").forEach(function (field) {
        field.addEventListener("change", function () {
            form.submit();
        });
    });
});
