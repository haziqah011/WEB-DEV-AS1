<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Contact Us | PawHome</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- NAVIGATION BAR -->
    <header>
        <h1>PawHome 🐾</h1>

        <nav>
            <a href="index.php">Home</a>
            <a href="pets.php">Adopt a Pet</a>
            <a href="about_us.php">About Us</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>


    <main class="contact-page">

        <section class="contact-hero">

            <span class="section-label">
                GET IN TOUCH
            </span>

            <h1>We'd love to hear from you.</h1>

            <p>
                Have a question about adoption?
                Fill in the form below!
            </p>

        </section>


        <!-- CONTACT FORM -->
        <section class="contact-section">

            <div class="contact-form-container">

                <form id="contactForm">

                    <!-- NAME -->
                    <div class="contact-field">

                        <label for="contact-name">
                            Your Name
                        </label>

                        <input
                            type="text"
                            id="contact-name"
                            name="name"
                            placeholder="Enter your name"
                            required>

                    </div>


                    <!-- EMAIL -->
                    <div class="contact-field">

                        <label for="contact-email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="contact-email"
                            name="email"
                            placeholder="Enter your email"
                            required>

                    </div>


                    <!-- SUBJECT -->
                    <div class="contact-field">

                        <label for="contact-subject">
                            Subject
                        </label>

                        <input
                            type="text"
                            id="contact-subject"
                            name="subject"
                            placeholder="What is your message about?"
                            required>

                    </div>


                    <!-- MESSAGE -->
                    <div class="contact-field">

                        <label for="contact-message">
                            Your Message
                        </label>

                        <textarea
                            id="contact-message"
                            name="message"
                            rows="6"
                            placeholder="Write your message here..."
                            required></textarea>

                    </div>


                    <!-- SUBMIT BUTTON -->
                    <button
                        type="submit"
                        class="contact-submit">

                        Send Message →

                    </button>

                </form>

            </div>

        </section>

    </main>


<!-- FOOTER -->

<footer class="home-footer">
    <div class="footer-container">

        <!-- PawHome -->
        <div class="footer-brand">
            <h2>PawHome 🐾</h2>
            <p>Helping little paws find forever homes.</p>
        </div>

        <!-- Explore -->
        <div class="footer-section">
            <h3>Explore</h3>
            <a href="index.php">Home</a>
            <a href="about_us.php">About Us</a>
            <a href="pets.php">Pet Adoption</a>
        </div>

        <!-- Get in Touch -->
        <div class="footer-section">
            <h3>Get in Touch</h3>
            <a href="contact.php">Contact Us</a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 PawHome. All rights reserved.</p>
    </div>
</footer>


    <!-- FORM PREVIEW -->
    <script>
        document.getElementById("contactForm")
            .addEventListener("submit", function(event) {

                event.preventDefault();

                alert(
                    "This contact form is currently a preview. " +
                    "Message sending will be available once " +
                    "the backend is connected."
                );

            });
    </script>

</body>
</html>
