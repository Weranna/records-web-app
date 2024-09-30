<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once "controllers/dictcontr.inc.php";
require_once 'models/dictmodel.inc.php';

if (isset($_GET['id']) && isset($_GET['table'])) {
    unset($_SESSION['errors']);
    unset($_SESSION['success']);
    $table = $_GET['table'];
    $id = $_GET['id'];

    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        delDictionary($table,$pdo,$id);

    } catch (PDOException $e) {
        // Sprawdzanie specyficznego kodu błędu dla klucza obcego
        handleDatabaseErrorOnDelete($e);
    }

} else {
    header("Location: ../public/dictionaries.php");
    die();
}

