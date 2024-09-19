<?php
declare(strict_types=1);

function getLocations($pdo) {
    $stmt = $pdo->prepare("SELECT name FROM locations");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo = null;
    $stmt = null;

    return $result;
}

