<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $project = $_POST["project"];
  $message = $_POST["message"];

  // Email settings
  $to = "akanewt22@gmail.com"; // 
  $subject = "New Project Submission";
  $body = "Name: $name\nEmail: $email\nProject Type: $project\nMessage:\n$message";

  // Handle file uploads
  $attachments = "";
  foreach ($_FILES["file"]["tmp_name"] as $key => $tmp_name) {
    $file_name = $_FILES["file"]["name"][$key];
    $file_tmp = $_FILES["file"]["tmp_name"][$key];
    $destination = "uploads/" . basename($file_name);
    move_uploaded_file($file_tmp, $destination);
    $attachments .= "\nUploaded File: $destination";
  }

  // Append file info to email body
  $body .= $attachments;

  // Send email
  mail($to, $subject, $body);

  // Redirect or show success
  echo "Thank you! Your message has been sent.";
}
?>
