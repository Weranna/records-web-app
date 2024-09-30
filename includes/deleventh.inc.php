<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/event.inc.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $event = new Event();

    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection();

        $event->deleteFromDatabase($pdo, $id);

        header("Location: ../public/homepage.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
    }
} else {
  
    header("Location: ../public/homepage.php");
    exit();
}
