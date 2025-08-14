<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $project = htmlspecialchars(trim($_POST["project"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!$email) {
        echo "❌ Invalid email address.";
        exit;
    }
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'akanewt22@gmail.com'; // Your Gmail
        $mail->Password = 'sebnfdkkwzdsdaxg';     // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email headers
        $mail->setFrom($email, $name);
        $mail->addAddress('akanewt22@gmail.com'); // Your receiving email
        $mail->addReplyTo($email, $name);
        $mail->Subject = "New Project Submission";

        // Optional: Request read receipt (may be ignored by Gmail)
        $mail->addCustomHeader("Disposition-Notification-To: akanewt22@gmail.com");

        // Optional: Add delivery status notification (DSN)
        $mail->ConfirmReadingTo = 'akanewt22@gmail.com';

        // Email body
        $mail->isHTML(true);
        $mail->Body = "
            <strong>Name:</strong> {$name}<br>
            <strong>Email:</strong> {$email}<br>
            <strong>Project Type:</strong> {$project}<br>
            <strong>Message:</strong><br>" . nl2br($message);
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nProject Type: {$project}\nMessage:\n{$message}";

        // Handle file uploads
        if (!empty($_FILES['file']['name'][0])) {
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['file']['name'][$key]);
                $file_tmp = $_FILES['file']['tmp_name'][$key];
                $file_size = $_FILES['file']['size'][$key];
                $file_type = mime_content_type($file_tmp);

                $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword'];
                if (is_uploaded_file($file_tmp) && in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
                    $mail->addAttachment($file_tmp, $file_name);
                }
            }
        }

        // Send the email
        if ($mail->send()) {
            echo "✅ Thank you! Your message has been sent successfully.";
        } else {
            echo "⚠️ Message was not sent. Please check your SMTP settings.";
        }
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        echo "❌ Message could not be sent. Please try again later.";
    }
}
?>
