<?php
require_once '../includes/config/dbh.inc.php';
require_once '../includes/config/config.php';

// Wyświetlanie komunikatu o błędzie/sukcesie
if (isset($_SESSION['errors'])) {
    echo '<div class="message error show">';
    foreach ($_SESSION['errors'] as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';
    unset($_SESSION['errors']);
}