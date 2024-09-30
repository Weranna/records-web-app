<?php
declare(strict_types=1);

require_once 'config/config.php';
require_once 'classes/dbh.inc.php';
require_once 'classes/user.inc.php';
require_once 'classes/login.inc.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    unset($_SESSION['errors'], $_SESSION['formData']);
    $_SESSION['errors'] = [];

    $login = htmlspecialchars(trim($_POST["login"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    $errors = Login::isInputEmpty($login, $password);

    if (!empty($errors)) {
        $_SESSION['formData'] = ['login' => $login];
        $_SESSION['errors'] = $errors;
        header("Location: ../public/index.php");
        exit();
    }

    try {
        $db = new Dbh();
        $pdo = $db->getConnection(); 

        $result = Login::getUser($pdo, $login);

        if (Login::isLoginInvalid($result)) { 
            $_SESSION['errors'][] = 'Niepoprawny login';
            header("Location: ../public/index.php");
            exit();
        }

        $user = new User(
            $result['login'],
            $result['pwd'],
            $result['email'],
            $result['user_group'],
            $result['location']
        );

        if ($user->verifyPassword($password)) {
      
            $newSessionId = session_create_id();
            session_id($newSessionId . "_" . $result['id']);

            $_SESSION["user_id"] = $result['id'];
            $_SESSION['login'] = htmlspecialchars($user->getLogin());
            $_SESSION['user_type'] = htmlspecialchars($user->getUserType());
            $_SESSION['user_location'] = htmlspecialchars($user->getUserLocation());
            $_SESSION['last_regeneration'] = time();

            header('Location: ../public/homepage.php');
            exit();
        } else {
            $_SESSION['formData'] = ['login' => $login];
            $_SESSION['errors'][] = 'Niepoprawne hasło';
            header("Location: ../public/index.php");
            exit();
        }
    } catch (PDOException $e) { 
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../public/index.php");
        exit();
    }
} else {
    header("Location: ../public/index.php");
    exit();
}
