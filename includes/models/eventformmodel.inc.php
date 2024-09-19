<?php
require_once '../includes/config/dbh.inc.php';
require_once '../includes/config/config.php';

if (isset($_POST['nrInv'])) {
    $_SESSION['nrInv'] = $_POST['nrInv'];
}

$stmt = $pdo->query("SELECT name FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);


$pdo = null;
$stmt = null;