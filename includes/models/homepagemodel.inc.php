<?php
declare(strict_types=1);
require_once '../includes/config/config.php';
require_once '../includes/classes/dbh.inc.php';

$db = new Dbh();
$pdo = $db->getConnection(); 


$userLocation = $_SESSION['user_location'];
$userType = $_SESSION['user_type'];

$sortColumn = $_GET['sortColumn'] ?? 'nrInv'; 
$sortOrder = $_GET['sortOrder'] ?? 'ASC';

$validColumns = ['nrInv', 'model', 'purchaseDate', 'warrantyDate', 'reviewDate', 'value'];
$sortColumn = in_array($sortColumn, $validColumns) ? $sortColumn : 'nrInv';
$sortOrder = $sortOrder === 'DESC' ? 'DESC' : 'ASC';

    $device = $_GET['device'] ?? '';
    $manufacturer = $_GET['manufacturer'] ?? '';
    $location = $_GET['location'] ?? '';
    $supplier = $_GET['supplier'] ?? '';
    $status = $_GET['status'] ?? '';
    $purchaseDate = $_GET['purchaseDate'] ?? '';
    $warrantyDate = $_GET['warrantyDate'] ?? '';
    $reviewDate = $_GET['reviewDate'] ?? '';

    $url = "?" . "device=" . urlencode($device) . 
       "&manufacturer=" . urlencode($manufacturer) . 
       "&location=" . urlencode($location) . 
       "&supplier=" . urlencode($supplier) . 
       "&status=" . urlencode($status) . 
       "&purchaseDate=" . urlencode($purchaseDate) . 
       "&warrantyDate=" . urlencode($warrantyDate) . 
       "&reviewDate=" . urlencode($reviewDate);

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

    $device = $_GET['device'] ?? '';
    $manufacturer = $_GET['manufacturer'] ?? '';
    $location = $_GET['location'] ?? '';
    $supplier = $_GET['supplier'] ?? '';
    $status = $_GET['status'] ?? '';
    $purchaseDate = $_GET['purchaseDate'] ?? '';
    $warrantyDate = $_GET['warrantyDate'] ?? '';
    $reviewDate = $_GET['reviewDate'] ?? '';

    //formatowanie daty
    $purchaseDate = $purchaseDate ? (new DateTime($purchaseDate))->format('Y-m-d') : '';
    $warrantyDate = $warrantyDate ? (new DateTime($warrantyDate))->format('Y-m-d') : '';
    $reviewDate = $reviewDate ? (new DateTime($reviewDate))->format('Y-m-d') : '';

    $whereClauses = [];
    $params = [];

    //przygotowywanie sql dla potencjalnego filtrowania
    if ($device && $device !== 'none') {
        $whereClauses[] = "device = :device";
        $params[':device'] = $device;
    }
    if ($manufacturer && $manufacturer !== 'none') {
        $whereClauses[] = "manufacturer = :manufacturer";
        $params[':manufacturer'] = $manufacturer;
    }
    if ($location && $location !== 'none') {
        $whereClauses[] = "location = :location";
        $params[':location'] = $location;
    }
    if ($supplier && $supplier !== 'none') {
        $whereClauses[] = "supplier = :supplier";
        $params[':supplier'] = $supplier;
    }
    if ($status && $status !== 'none') {
        $whereClauses[] = "status = :status";
        $params[':status'] = $status;
    }
    if ($purchaseDate) {
        $whereClauses[] = "purchaseDate = :purchaseDate";
        $params[':purchaseDate'] = $purchaseDate;
    }
    if ($warrantyDate) {
        $whereClauses[] = "warrantyDate = :warrantyDate";
        $params[':warrantyDate'] = $warrantyDate;
    }
    if ($reviewDate) {
        $whereClauses[] = "reviewDate = :reviewDate";
        $params[':reviewDate'] = $reviewDate;
    }

    if ($userType !== 'admin') {
        $whereClauses[] = "location = :userLocation";
        $params[':userLocation'] = $userLocation;
    }
    
    $whereSql = '';
    if (!empty($whereClauses)) {
        $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
    }
    
    $sql = "SELECT nrInv, nrSer, device, manufacturer, model, location, supplier, purchaseDate, warrantyDate, reviewDate, value, status, notes 
            FROM equipments 
            $whereSql
            ORDER BY $sortColumn $sortOrder";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);