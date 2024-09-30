<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'classes/dictionary.inc.php';

if (isset($_GET['id']) && isset($_GET['table'])) {
    unset($_SESSION['errors']);
    unset($_SESSION['success']);
    $table = $_GET['table'];
    $id = (int)$_GET['id'];

    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection();

        // Wywołanie metody delete
        Dictionary::delete($pdo, $table,$id);

    } catch (PDOException $e) {
        Dictionary::handleDatabaseErrorOnDelete($e);
    }

} else {
    header("Location: ../public/dictionaries.php");
    die();
}
