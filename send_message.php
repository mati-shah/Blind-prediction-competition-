<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Honeypot check
    if (!empty($_POST['website'])) exit;

    // Google reCAPTCHA verification
    $recaptchaSecret = 'YOUR_SECRET_KEY';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        echo "reCAPTCHA verification failed. Please try again.";
        exit;
    }

    // Sanitize input
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $to = "mati.shah@epfl.ch";
    $subject = "Contact Form Submission from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Thank you! Your message has been sent.";
    } else {
        echo "Oops! Something went wrong. Please try again.";
    }

} else {
    echo "Invalid request.";
}
?>
