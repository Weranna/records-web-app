
<?php
require_once '../includes/config/dbh.inc.php';
require_once '../includes/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: homepage.php");
    exit();
}

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header('Location: homepage.php');
    exit();
}

// Wyświetlanie komunikatu o błędzie/sukcesie
if (isset($_SESSION['errors'])) {
    echo '<div class="message error show">';
    foreach ($_SESSION['errors'] as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';
    unset($_SESSION['errors']);
}