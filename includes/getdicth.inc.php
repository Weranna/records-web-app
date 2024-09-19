<?php
require_once 'config/config.php';
require_once 'models/dictmodel.inc.php';

if (isset($_GET['table'], $_GET['id'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];

    require_once 'config/dbh.inc.php';

    $row = getDictionary($pdo,$id,$table);

    if ($row) {
        echo json_encode(['status' => 'success', 'formData' => $row]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Element not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect query']);
    die();
}

