<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'models/eventmodel.inc.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        require_once "config/dbh.inc.php";
        delEvent($pdo,$id);

    } catch (PDOException $e) {
        $_SESSION['errors'] = $e->getMessage();
    }
} else {
    header("Location: ../public/homepage.php");
    die();
}
