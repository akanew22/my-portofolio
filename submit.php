<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
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

        if (!empty($_FILES['file']['name'][0])) {
    // Define allowed MIME types based on your input's accept attribute
    $allowed_types = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'text/plain',
        'application/rtf',
        'application/zip',
        'application/x-rar-compressed',
        'audio/mpeg',
        'audio/wav',
        'video/mp4',
        'video/x-msvideo'
    ];

    foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['file']['name'][$key]);
        $file_tmp = $_FILES['file']['tmp_name'][$key];
        $file_size = $_FILES['file']['size'][$key];
        $file_type = mime_content_type($file_tmp);

        // Validate file type and size (max 5MB)
        if ($_FILES['file']['error'][$key] === UPLOAD_ERR_OK &&
    in_array($file_type, $allowed_types) &&
    $file_size <= 5 * 1024 * 1024) {
    $mail->addAttachment($file_tmp, $file_name);
}

    }
}


        // Send the email
        if ($mail->send()) {
    header("Location: thankyou.html");
    exit;
} else {
    echo "⚠️ Message was not sent. Please check your SMTP settings.";
}

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        echo "❌ Message could not be sent. Please try again later.";
    }
}
?>
