<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'controllers/eventcontr.inc.php';
require_once 'models/eventmodel.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    unset($_SESSION['errors']);
    unset($_SESSION['formData']);

    $nrInv = $_SESSION['nrInv'];
    unset($_SESSION['nrInv']);

    $_SESSION['errors'] = [];

    // Pobieranie danych z formularza
    $event = 'awaria';
    $beginDate = htmlspecialchars($_POST['beginDate']);
    $endDate = htmlspecialchars($_POST['endDate']);
    $description = htmlspecialchars($_POST['description']);

    // Plik
    $filePath = handleFile();
    
    // Sprawdzenie, czy pola są puste
    $errors = isInputEmpty($event,$beginDate,$endDate,$description);

    if (count($errors) > 0) {
        // Przechowywanie wartości formularza w sesji
        $_SESSION['formData'] = [
            'beginDate' => $beginDate,
            'endDate' => $endDate,
            'description' => $description,
        ];
        $_SESSION['errors'] = $errors;
        header("Location: ../public/reportform.php");
        exit();
    } 
    
    try {
        // Wysłanie danych do bazy danych
        require_once "config/dbh.inc.php";
        addEvent($pdo,$nrInv, $event, $beginDate, $endDate, $description, $filePath);
    
    } catch (PDOException $e) {
        $_SESSION['errors'] []= $e->getMessage();
        }
    }
 else {
    header("Location: ../public/homepage.php");
    die();
}
