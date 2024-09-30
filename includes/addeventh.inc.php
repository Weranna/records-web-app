<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/event.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    unset($_SESSION['errors']);
    unset($_SESSION['formData']);

    $nrInv = $_SESSION['nrInv'];
    unset($_SESSION['nrInv']);

    $_SESSION['errors'] = [];

    // Pobieranie danych z formularza
    $eventName = $_POST['event'] ?? '';
    $beginDate = $_POST['beginDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$nrInv) {
        $_SESSION['errors'][] = 'Numer inwentarzowy jest wymagany.';
        header("Location: ../public/eventform.php");
        exit();
    }

    $event = new Event($eventName, $beginDate, $endDate, $description);

    $validationErrors = $event->validate();

    if (count($validationErrors) > 0) {
        $_SESSION['formData'] = [
            'event' => $eventName,
            'beginDate' => $beginDate,
            'endDate' => $endDate,
            'description' => $description,
        ];
        $_SESSION['errors'] = $validationErrors;
        header("Location: ../public/eventform.php");
        exit();
    }

    try {
        $event->handleFileUpload();

        require_once 'classes/dbh.inc.php';
        $db = new Dbh();
        $pdo = $db->getConnection();
        $event->addToDatabase($pdo, $nrInv);
        
        header("Location: ../public/homepage.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../public/eventform.php");
        exit();
    }
}

