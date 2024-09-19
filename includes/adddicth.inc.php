<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'controllers/dictcontr.inc.php';
require_once 'models/dictmodel.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION['errors']);

    $table = $_POST['table'] ?? '';

    require_once "config/dbh.inc.php";

    $tableMapping = [
        'devices' => ['name'],
        'manufacturers' => ['name'],
        'suppliers' => ['name', 'address', 'phone', 'email'],
        'locations' => ['name'],
        'statuses' => ['name'],
        'users' => ['login', 'pwd', 'user_group', 'email', 'location'],
        'events' => ['name']
    ];

    $validationResult = validateForm($_POST, $tableMapping[$table]);

    if (!empty($validationResult['errors'])) {
        $_SESSION['errors'] = $validationResult['errors'];
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }

    $columns = array_keys($validationResult['values']);
    $values = array_values($validationResult['values']);

    // Obsługuje hasło i walidację e-maila
    if ($table === 'users') {

        $values = hashPwd($values, $columns);

        if(isEmailInvalid($values[array_search('email', $columns)])) {
            $_SESSION['errors'] [] = 'Podaj poprawny email';
            echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
            exit();
        }
    }

    try {
    
        addDictionary($table, $columns, $pdo, $values);

    } catch (PDOException $e) {

        handleDatabaseError($e, $tableMapping[$table], $validationResult);
    }
}
