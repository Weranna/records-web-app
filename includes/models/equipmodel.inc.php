<?php
declare(strict_types=1);

function  addEquip($pdo,$serNumber,$device,$manufacturer,$model,$location,$supplier,$purchaseDate,$warrantyDate,$reviewDate,$value,$status,$notes,$filePath)  {
        
        $query = "INSERT INTO equipments (nrSer, device, manufacturer, model, location, supplier, purchaseDate, warrantyDate, reviewDate, value, status, image, notes)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status, $filePath, $notes]);
        
        $pdo = null;
        $stmt = null;

        unset($_SESSION['formData']);
        $_SESSION['success'] = true;
        
        header("Location: ../public/homepage.php");
        exit();
}

function delEquip($pdo, $nrInv) {

     // Pobranie ścieżki pliku zdjęcia przed usunięciem rekordu
     $query = "SELECT image FROM equipments WHERE nrInv = ?";
     $stmt = $pdo->prepare($query);
     $stmt->execute([$nrInv]);
     $imagePath = $stmt->fetchColumn();

     // Usunięcie wszystkich zdarzeń powiązanych z numerem inwentaryzacyjnym
     $query = "DELETE FROM equip_events WHERE equipId = ?";
     $stmt = $pdo->prepare($query);
     $stmt->execute([$nrInv]);
     
     $query = "DELETE FROM equipments WHERE nrInv = ?";
     $stmt = $pdo->prepare($query);
     $stmt->execute([$nrInv]);

     // Usunięcie pliku zdjęcia z folderu, jeśli istnieje
     if ($imagePath && file_exists($imagePath)) {
         unlink($imagePath);
     }

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
