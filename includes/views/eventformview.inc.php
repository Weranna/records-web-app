<?php
require_once '../includes/config/config.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}    

    if (isset($_SESSION['errors'])) {
        echo '<div class="message error show">';
        foreach ($_SESSION['errors'] as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
        unset($_SESSION['errors']);
    }