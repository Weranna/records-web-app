<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/user.inc.php';
require_once 'controllers/logincontr.inc.php';
require_once 'models/loginmodel.inc.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    unset($_SESSION['errors']);
    unset($_SESSION['formData']);
    $_SESSION['errors'] = [];

    $login = htmlspecialchars($_POST["login"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors = isInputEmpty($login, $password);

    if (count($errors) > 0) {
        $_SESSION['formData'] = ['login' => $login];
        $_SESSION['errors'] = $errors;
        header("Location: ../public/index.php");
        exit();
    }

    try {

        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        $result = getUser($pdo, $login);

        if (isLoginInvalid($result)) { 
            $_SESSION['errors'][] = 'Niepoprawny login';
            header("Location: ../public/index.php");
            exit();
        }

        // Utwórz obiekt User
        $user = new User(
            (int)$result['id'],
            $result['login'],
            $result['pwd'], // Hasło haszowane
            $result['email'],
            $result['user_group'],
            $result['location']
        );

        // Weryfikacja hasła
        if (!$user->verifyPassword($password)) {
            $_SESSION['formData'] = ['login' => $login];
            $_SESSION['errors'][] = 'Niepoprawne hasło';
            header("Location: ../public/index.php");
            exit();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $user->getId();
        session_id($sessionId);

        $_SESSION["user_id"] = $user->getId();
        $_SESSION['login'] = htmlspecialchars($user->getLogin());
        $_SESSION['user_type'] = htmlspecialchars($user->getUserType());
        $_SESSION['user_location'] = htmlspecialchars($user->getUserLocation());
        $_SESSION['last_regeneration'] = time();

        header('Location: ../public/homepage.php');
        die();

    } catch (PDOException $e) { 
        $_SESSION['errors'][] = $e->getMessage();
    }
} else {
    header("Location: ../public/index.php");
    die();
}
