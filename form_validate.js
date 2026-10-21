// Quick checks in the browser. The server (form.php) validates everything again.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form.adoption-form");
    if (!form) return;

    form.addEventListener("submit", function (event) {
        const problems = [];
        const val = (id) => form.querySelector("#" + id).value.trim();

        if (val("fullname").length < 2) problems.push("Enter your full name.");
        if (!/^\S+@\S+\.\S+$/.test(val("email"))) problems.push("Enter a valid email address.");
        if (!/^[0-9+\-\s()]{7,20}$/.test(val("phone"))) problems.push("Enter a valid phone number (7 to 20 digits).");
        if (val("address") === "") problems.push("Enter your home address.");
        if (val("housing") === "") problems.push("Select your housing type.");
        if (val("experience") === "") problems.push("Tell us if you have owned pets before.");
        if (val("reason").length < 10) problems.push("Tell us a little more about why you want to adopt (at least 10 characters).");
        if (!form.querySelector("#agree").checked) problems.push("Please confirm your information is accurate.");

        const old = form.querySelector(".js-errors");
        if (old) old.remove();

        if (problems.length) {
            event.preventDefault();
            const box = document.createElement("div");
            box.className = "form-message error js-errors";
            box.setAttribute("role", "alert");
            box.innerHTML = "<strong>Please fix the following:</strong><ul>" +
                problems.map(p => "<li>" + p + "</li>").join("") + "</ul>";
            form.prepend(box);
            box.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
});
