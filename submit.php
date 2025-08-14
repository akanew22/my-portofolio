<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "POST request received!";
} else {
    echo "Only POST requests are allowed.";
}
?>
