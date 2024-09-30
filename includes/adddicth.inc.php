<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'classes/dictionary.inc.php';
require_once 'classes/user.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    unset($_SESSION['errors']);

    $table = $_POST['table'] ?? '';

    $dictionary = null;

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
            if (isset($_POST['login'], $_POST['pwd'], $_POST['email'], $_POST['user_group'], $_POST['location'])) {
                $login = htmlspecialchars($_POST['login']);
                $email = htmlspecialchars($_POST['email']);
                $userType = htmlspecialchars($_POST['user_group']);
                $userLocation = htmlspecialchars($_POST['location']);
                
                $hashedPassword = User::hashPassword($_POST['pwd']);
                $dictionary = new User($login, $hashedPassword, $email, $userType, $userLocation);
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
        $validationResult['errors'] = User::validateUserData($_POST);
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
        
        if($table === 'users') {
            $dictionary->addToDatabase($pdo);
        } else {
            $dictionary->add($pdo);
        }
    } catch (PDOException $e) {
        $dictionary->handleDatabaseError($e);
    }
}
