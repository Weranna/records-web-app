<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'classes/dictionary.inc.php';
require_once 'classes/user.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION['errors']);

    $table = $_POST['table'] ?? '';
    $id = $_POST['id'] ?? ''; // ID do edycji
    $dictionary = null;

    $tableMapping = [
        'devices' => ['name'],
        'manufacturers' => ['name'],
        'suppliers' => ['name', 'address', 'phone', 'email'],
        'locations' => ['name'],
        'statuses' => ['name'],
        'users' => ['login', 'user_group', 'email', 'location'],
        'events' => ['name']
    ];

    // Ustalenie, która klasa słownika powinna być użyta
    switch ($table) {
        case 'devices':
            $dictionary = new DeviceDictionary();
            break;
        case 'manufacturers':
            $dictionary = new ManufacturerDictionary();
            break;
        case 'suppliers':
            $dictionary = new SupplierDictionary();
            break;
        case 'locations':
            $dictionary = new LocationDictionary();
            break;
        case 'statuses':
            $dictionary = new StatusDictionary();
            break;
        case 'users':
            if (isset($_POST['login'], $_POST['email'], $_POST['user_group'], $_POST['location'])) {
                $login = htmlspecialchars($_POST['login']);
                $email = htmlspecialchars($_POST['email']);
                $userType = htmlspecialchars($_POST['user_group']);
                $userLocation = htmlspecialchars($_POST['location']);
                
                $dictionary = new User($login, '', $email, $userType, $userLocation);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych dla użytkownika.']);
                exit();
            }
            break;
        case 'events':
            $dictionary = new EventDictionary();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Nieznany typ słownika.']);
            exit();
    }

    $validationResult = [];

    if ($table === 'users') {
        $validationResult['errors'] = User::validateUserDataOnEdit($_POST);
    } else {
        $validationResult = $dictionary->validate($_POST);
    }

    if (!empty($validationResult['errors'])) {
        $_SESSION['errors'] = $validationResult['errors'];
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }

    try {
        require_once 'classes/dbh.inc.php';
        
        $db = new Dbh();
        $pdo = $db->getConnection();
        
        // Edycja w bazie
        if ($table === 'users') {
            $dictionary->editUserInDatabase($pdo, (int)$id);
        } else {
            $dictionary->edit($pdo, (int)$id);
        }
    } catch (PDOException $e) {
        $dictionary->handleDatabaseErrorOnEdit($e,$tableMapping,$validationResult);
    }
} else {
    die();
}
