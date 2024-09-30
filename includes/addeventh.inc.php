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
    $event = htmlspecialchars($_POST['event']);
    $beginDate = htmlspecialchars($_POST['beginDate']);
    $endDate = htmlspecialchars($_POST['endDate']);
    $description = htmlspecialchars($_POST['description']);
    
    if (!$nrInv) {
        $_SESSION['errors'] [] = 'pusto';
        header("Location: ../public/eventform.php");
        exit();
     }

    // Plik
    $filePaths = handleFile();

    // Sprawdzenie, czy pola są puste
    $errors = isInputEmpty($event,$beginDate,$endDate,$description);

    if (count($errors) > 0) {
        // Przechowywanie wartości formularza w sesji
        $_SESSION['formData'] = [
            'event' => $event,
            'beginDate' => $beginDate,
            'endDate' => $endDate,
            'description' => $description,
        ];
        $_SESSION['errors'] = $errors;
        header("Location: ../public/eventform.php");
        exit();
    } 
    
    try {
        // Wysłanie danych do bazy danych
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        addEvent($pdo,$nrInv, $event, $beginDate, $endDate, $description, $filePaths);
    
    } catch (PDOException $e) {
        $_SESSION['errors'] [] = $e->getMessage();
        echo $e->getMessage();
        }
    }
 else {
    header("Location: ../public/homepage.php");
    die();
}
