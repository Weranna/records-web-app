<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/equipment.inc.php';

if (isset($_GET['nrInv'])) {
    $nrInv = htmlspecialchars($_GET['nrInv']);

    $equipment = new Equipment();

    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        $equipment->delEquip($nrInv,$pdo);

    } catch (PDOException $e) {
        $_SESSION['errors'] []= $e->getMessage();
            exit();
    }
} else {
    header("Location: ../public/homepage.php");
    die();
}
