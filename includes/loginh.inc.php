<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'models/loginmodel.inc.php';
require_once 'controllers/logincontr.inc.php'
;
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    unset($_SESSION['errors']);
    unset($_SESSION['formData']);

    $_SESSION['errors'] = [];

    $login = htmlspecialchars($_POST["login"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors = isInputEmpty($login,$password);

    if (count($errors) > 0) {

        // Przechowywanie wartości formularza w sesji
        $_SESSION['formData'] = [
            'login' => $login,
        ];
        $_SESSION['errors'] = $errors;
        header("Location: ../public/index.php");
        exit();
    } 

    try {
        require_once 'config/dbh.inc.php';

        $result = getUser($pdo, $login);

        if(isLoginInvalid($result)) { 
            $_SESSION['errors'] [] = 'Niepoprawny login';
            header("Location: ../public/index.php");
            exit();
        }
        if(isPasswordInvalid($password, $result['pwd'])) {
            $_SESSION['formData'] = [
                'login' => $login,
            ]; 
            $_SESSION['errors'] [] = 'Niepoprawne hasło';
            header("Location: ../public/index.php");
            exit();
         }

         $newSessionId = session_create_id();
         $sessionId = $newSessionId . "_" . $result["id"];
         session_id($sessionId );

         $_SESSION["user_id"] = $result['id'];
         $_SESSION['login'] = htmlspecialchars($result['login']);
         $_SESSION['user_type'] = htmlspecialchars($result['user_group']);
         $_SESSION['user_location'] = htmlspecialchars($result['location']);

         $_SESSION['last_regeneration'] = time();

         header('Location: ../public/homepage.php');
         die();

    } catch (PDOException $e) { 

        $_SESSION['errors'] = $e->getMessage();
    }
} else {
    header("Location: ../public/index.php");
    die();
}