<?php
require_once '../includes/config/dbh.inc.php';
require_once '../includes/config/config.php';

$stmt = $pdo->query("SELECT name FROM devices");
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT name FROM manufacturers");
    $manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT name FROM suppliers");
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT name FROM locations");
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT name FROM statuses");
    $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo = null;
    $stmt = null;