<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/equipment.inc.php';

// Sprawdzenie, czy żądanie to POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    unset($_SESSION['errors']);
    unset($_SESSION['formData']);

    // Pobieranie danych z formularza
    $nrInv = htmlspecialchars($_POST['nrInv']);
    $serNumber = htmlspecialchars($_POST["serNumber"]);
    $device = htmlspecialchars($_POST["device"]);
    $manufacturer = htmlspecialchars($_POST["manufacturer"]);
    $model = htmlspecialchars($_POST["model"]);
    $location = htmlspecialchars($_POST["location"]);
    $supplier = htmlspecialchars($_POST["supplier"]);
    $purchaseDate = htmlspecialchars($_POST["purchaseDate"]);
    $warrantyDate = htmlspecialchars($_POST["warrantyDate"]);
    $reviewDate = htmlspecialchars($_POST["reviewDate"]);
    $value = filter_input(INPUT_POST, 'value', FILTER_VALIDATE_FLOAT);
    $status = htmlspecialchars($_POST["status"]);
    $notes = htmlspecialchars($_POST["notes"]);
    
    $equipment = new Equipment();

    $equipment->setDetails($serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $notes);
    
    $errors = $equipment->isInputEmpty($serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status);
    
    if (count($errors) > 0) {
        // Przechowywanie wartości formularza w sesji
        $_SESSION['formData'] = [
            'nrInv' => $nrInv,
            'serNumber' => $serNumber,
            'device' => $device,
            'manufacturer' => $manufacturer,
            'model' => $model,
            'location' => $location,
            'supplier' => $supplier,
            'purchaseDate' => $purchaseDate,
            'warrantyDate' => $warrantyDate,
            'reviewDate' => $reviewDate,
            'value' => $value,
            'status' => $status,
            'notes' => $notes
        ];

        $_SESSION['errors'] = $errors;
        header("Location: ../public/equipformedit.php?nrInv=$nrInv");
        exit();
    } 
    
    try {

        require_once 'classes/dbh.inc.php';
    $db = new Dbh();
    $pdo = $db->getConnection();
        
        $equipment->editEquip($nrInv, $pdo);
        
    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header("Location: ../public/equipformedit.php?nrInv=$nrInv");
        exit();
    }
} else {
    header("Location: ../public/homepage.php");
    die();
}
