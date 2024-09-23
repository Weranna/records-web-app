<?php
declare(strict_types=1);
function addEquip($pdo, $serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $notes, $filePaths) {

    $query = "INSERT INTO equipments (nrSer, device, manufacturer, model, location, supplier, purchaseDate, warrantyDate, reviewDate, value, status, notes)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $notes]);

    $itemId = $pdo->lastInsertId();

    // Wstawianie zdjęć do tabeli files
    if (!empty($filePaths)) {
        $fileQuery = "INSERT INTO files (nrInv, file_path) VALUES (?, ?)";
        $fileStmt = $pdo->prepare($fileQuery);

        foreach ($filePaths as $filePath) {
            $fileStmt->execute([$itemId, $filePath]);
        }
    }

    $pdo = null;
    $stmt = null;
    $fileStmt = null;

    unset($_SESSION['formData']);
    $_SESSION['success'] = true;
    
    header("Location: ../public/homepage.php");
    exit();
}


function delEquip($pdo, $nrInv) {

    // Pobranie wszystkich ścieżek zdjęć przed usunięciem rekordu
    $query = "SELECT file_path FROM files WHERE nrInv = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nrInv]);
    $imagePaths = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Usunięcie wszystkich zdarzeń powiązanych z numerem inwentaryzacyjnym
    $query = "DELETE FROM equip_events WHERE equipId = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nrInv]);

    // Usunięcie rekordu z tabeli equipments
    $query = "DELETE FROM equipments WHERE nrInv = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nrInv]);

    // Usunięcie plików zdjęć z folderu
    foreach ($imagePaths as $imagePath) {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Usunięcie rekordów z tabeli files
    $query = "DELETE FROM files WHERE nrInv = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nrInv]);

    $pdo = null;
    $stmt = null;

    $_SESSION['success'] = true;

    header("Location: ../public/homepage.php");
    exit();
}


function editEquip ($pdo,$serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $notes, $nrInv) {
    $query = "UPDATE equipments 
          SET nrSer = ?, device = ?, manufacturer = ?, model = ?, location = ?, supplier = ?, purchaseDate = ?, warrantyDate = ?, reviewDate = ?, value = ?, status = ?, notes = ?
          WHERE nrInv = ?";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $notes, $nrInv]);
        
        $pdo = null;
        $stmt = null;

        unset($_SESSION['formData']);
        $_SESSION['success'] = true;
        header("Location: ../public/homepage.php");
        exit();
}
