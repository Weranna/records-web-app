<?php
declare(strict_types=1);

function addDictionary($table, $columns, $pdo,  $values) {
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $query = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)";

        $stmt = $pdo->prepare($query);
        $stmt->execute($values);

        echo json_encode(['status' => 'success', 'table' => $table]);
        $_SESSION['success'] = true;
        exit();
}

function delDictionary ($table,$pdo,$id) {
    $query = "DELETE FROM `$table` WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $pdo = null;
        $stmt = null;

        echo json_encode(['status' => 'success', 'table' => $table]);
        $_SESSION['success'] = true;
        exit();
}

function editDictionary ($table,$pdo,$columns,$values) {
    $query = "UPDATE $table SET " . implode(', ', $columns) . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute($values);

        $pdo = null;
        $stmt = null;

        echo json_encode(['status' => 'success', 'table' => $table]);
        $_SESSION['success'] = true;
        exit();
}

function getDictionary ($pdo,$id,$table) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo = null;
    $stmt = null;

    return $row;
}