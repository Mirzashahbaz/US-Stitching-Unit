<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require 'vendor/autoload.php'; // PHPMailer autoload
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stitching_center";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['service']) && !empty($_POST['message'])) {
        
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
        $service = mysqli_real_escape_string($conn, trim($_POST['service']));
        $message = mysqli_real_escape_string($conn, trim($_POST['message']));

        $sql = "INSERT INTO `contact_messages`(`name`, `email`, `phone`, `subject`, `message`) VALUES ('$name', '$email', '$phone', '$service', '$message')";
        
        if (mysqli_query($conn, $sql)) {

            if (sendEmailWithPHPMailer($name, $email, $phone, $service, $message)) {
                $_SESSION['success_message'] = "Thank you! Your message has been sent successfully.";
                echo "<script>window.location.href='contact.php';</script>";
            } else {
                $_SESSION['error_message'] = "Data saved, but email sending failed.";
            }
        } else {
            $_SESSION['error_message'] = "Database error: " . mysqli_error($conn);
        }

        header("Location: contact.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Please fill in all fields before submitting.";
        header("Location: contact.php");
        exit();
    }
}

// Function to send email using PHPMailer
function sendEmailWithPHPMailer($name, $email, $phone, $service, $message) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mirzashahbaz295@gmail.com'; 
        $mail->Password   = 'mxqt loso iliu nudk'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender & Recipient
        $mail->setFrom('mirzashahbaz295@gmail.com', 'Mirza Shahbaz');
        $mail->addAddress('mirzashahbaz295@gmail.com');

        // Email Content
        $mail->Subject = "New Contact Form Submission";
        $mail->Body    = "New message received:\n\n".
                         "Name: $name\n".
                         "Email: $email\n".
                         "Phone: $phone\n".
                         "Service: $service\n".
                         "Message: $message\n";

        // Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - USSU</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Loading Animation -->
    <div class="loading"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.html">
                <h1 title="US Stitching Unit">USSU</h1>
            </a>
        </div>
        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="services.html">Services</a>
            <a href="about.html">About Us</a>
            <a href="contact.php" class="active">Contact</a>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- Contact Hero -->
    <section class="contact-hero" style="background-image: url('images/ussu/contact-us-.webp');">
        <div class="hero-content">
            <h1>Get in Touch</h1>
            <p>We're here to help with all your tailoring needs</p>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact-info">
        <div class="section-title">
            <h2>Contact Information</h2>
            <p>Multiple ways to reach us</p>
        </div>
        <div class="contact-grid">
            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Visit Us</h3>
                <p>123 Stitch Street</p>
                <p>Fashion District</p>
                <p>City, State 12345</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-phone"></i>
                <h3>Call Us</h3>
                <p>Phone: (555) 123-4567</p>
                <p>Mobile: (555) 987-6543</p>
                <p>Fax: (555) 246-8135</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Email Us</h3>
                <p>info@stitchingcenter.com</p>
                <p>support@stitchingcenter.com</p>
                <p>bookings@stitchingcenter.com</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-clock"></i>
                <h3>Business Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday: 10:00 AM - 4:00 PM</p>
                <p>Sunday: Closed</p>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="contact-form-section">
        <div class="section-title">
            <h2>Send Us a Message</h2>
            <p>We'll get back to you as soon as possible</p>
        </div>
        <div class="form-container">
            <!--  Success Message -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

             <!-- Error Message -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?php echo $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?> 
            
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="service">Service Required</label>
                        <select id="service" name="service" required>
                            <option value="">Select a Service</option>
                            <option value="mens">Men's Tailoring</option>
                            <option value="womens">Women's Tailoring</option>
                            <option value="alterations">Alterations</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                </div>
                <button type="submit" name="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="section-title">
            <h2>Our Location</h2>
            <p>Find us easily</p>
        </div>
        <div class="map-container">
            
            <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11363.95760518456!2d73.10303372819875!3d31.445280442470082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3922683538aa49d1%3A0x8bebd564c014cb6c!2sBaghban%20Pura%20Faisalabad%2C%20Pakistan!5e1!3m2!1sen!2s!4v1741982452685!5m2!1sen!2s"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="section-title">
            <h2>Frequently Asked Questions</h2>
            <p>Find quick answers to common questions</p>
        </div>
        <div class="faq-grid">
            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> How long does a typical alteration take?</h3>
                <p>Most basic alterations are completed within 3-5 business days. Complex alterations may take 7-10 days.</p>
            </div>
            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Do I need an appointment?</h3>
                <p>While walk-ins are welcome, we recommend booking an appointment for custom tailoring services.</p>
            </div>
            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> What payment methods do you accept?</h3>
                <p>We accept all major credit cards, cash, and digital payments including Apple Pay and Google Pay.</p>
            </div>
            <div class="faq-item">
                <h3><i class="fas fa-question-circle"></i> Do you offer rush services?</h3>
                <p>Yes, we offer rush services for an additional fee, subject to availability.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Stitching Center</h3>
                <p>Your trusted partner for professional tailoring services. Quality craftsmanship since 1995.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="services.html">Services</a>
                <a href="about.html">About Us</a>
                <a href="contact.php">Contact</a>
                <a href="#">Book Appointment</a>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Stitch Street, Fashion District</p>
                <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                <p><i class="fas fa-envelope"></i> info@stitchingcenter.com</p>
                <p><i class="fas fa-clock"></i> Mon-Sat: 9:00 AM - 6:00 PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 US Stitching Unit. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
<?php
$conn->close();
?> 