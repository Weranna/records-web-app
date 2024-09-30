<?php
declare(strict_types=1);
require_once 'config/config.php';
require_once 'classes/equipment.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    unset($_SESSION['errors']);
    unset($_SESSION['formData']);

    $_SESSION['errors'] = [];

    // Fetch data from the form
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
    $errors = $equipment->isInputEmpty($serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status);

    if (count($errors) > 0) {
      
        $_SESSION['formData'] = [
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
        header("Location: ../public/equipform.php");
        exit();
    } 

    $equipment->setDetails(
        $serNumber,
        $device,
        $manufacturer,
        $model,
        $location,
        $supplier,
        $purchaseDate,
        $warrantyDate,
        $reviewDate,
        $value,
        $status,
        $notes
    );

    $filePaths = $equipment->handlePhoto();
    
    try {
        require_once 'classes/dbh.inc.php';

        $db = new Dbh();
        $pdo = $db->getConnection(); 

        $equipment->addEquip($filePaths,$pdo);
    } catch (PDOException $e) {
        $_SESSION['errors'][] = $e->getMessage();
        exit();
    }
} else {
    header("Location: ../public/homepage.php");
    die();
}
