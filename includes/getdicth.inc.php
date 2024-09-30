<?php
require_once 'config/config.php';
require_once 'classes/dictionary.inc.php';

if (isset($_GET['table'], $_GET['id'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];

    require_once 'classes/dbh.inc.php';

    $db = new Dbh();
    $pdo = $db->getConnection(); 

    $row = Dictionary::get($pdo, $table, $id);

    if ($row) {
        echo json_encode(['status' => 'success', 'formData' => $row]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Element not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect query']);
    die();
}

