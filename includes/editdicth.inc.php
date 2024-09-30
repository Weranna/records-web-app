<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'controllers/dictcontr.inc.php';
require_once 'models/dictmodel.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION['errors']);

    $table = $_POST['table'] ?? '';
    $id = $_POST['id'] ?? '';

    $tableMapping = [
        'devices' => ['name'],
        'manufacturers' => ['name'],
        'suppliers' => ['name', 'address', 'phone', 'email'],
        'locations' => ['name'],
        'statuses' => ['name'],
        'users' => ['login', 'user_group', 'email', 'location'],
        'events' => ['name']
    ];

    $validationResult = validateForm($_POST, $tableMapping[$table]);

    if (!empty($validationResult['errors'])) {
        $_SESSION['errors'] = $validationResult['errors'];
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }

    $columns = array_map(fn($column) => "$column = ?", $tableMapping[$table]);
    $values = array_values($validationResult['values']);
    $values[] = $id;

    if ($table === 'users' && isset($validationResult['values']['email'])) {
        if (isEmailInvalid($validationResult['values']['email'])) {
            $_SESSION['errors'][] = 'Podaj poprawny email';
            echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
            exit();
        }
    }

    try {

        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        editDictionary($table,$pdo,$columns,$values);
        
    } catch (PDOException $e) {
        handleDatabaseErrorOnEdit($e, $tableMapping[$table], $validationResult);
    }
} else {
    die();
}
