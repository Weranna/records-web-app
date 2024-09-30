<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'models/equipmodel.inc.php';

if (isset($_GET['nrInv'])) {
    $nrInv = htmlspecialchars($_GET['nrInv']);

    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        delEquip($pdo, $nrInv);

    } catch (PDOException $e) {
        $_SESSION['errors'] []= $e->getMessage();
            exit();
    }
} else {
    header("Location: ../public/homepage.php");
    die();
}
