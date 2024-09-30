<?php
require_once '../includes/classes/dbh.inc.php';
require_once '../includes/config/config.php';

$db = new Dbh();
$pdo = $db->getConnection(); 

// Pobieranie numeru inw
if (isset($_GET['nrInv'])) {
    $nrInv = $_GET['nrInv'];


    // Pobieranie danych o sprzęcie
    $query = "SELECT * FROM equipments WHERE nrInv = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nrInv]);
    $equipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipment) {
        die("Nie znaleziono sprzętu o podanym numerze inwentaryzacyjnym.");
    }

    // Wypełnianie formularza danymi
    $_SESSION['formData'] = $equipment;
} else {
    die("Brak numeru inwentaryzacyjnego do edytowania.");
}

// Pobieranie danych do opcji formularza
$stmt = $pdo->query("SELECT name FROM devices");
$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT name FROM manufacturers");
$manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT name FROM suppliers");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT name FROM locations");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT name FROM statuses");
$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
$stmt = null;