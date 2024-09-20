<?php
declare(strict_types=1);

function addEvent($pdo,$nrInv, $event, $beginDate, $endDate, $description, $filePath) {
    $query = "INSERT INTO equip_events (equipId, name, beginDate, endDate, description, file)
                  VALUES (?, ?, ?, ?, ?, ?);";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv, $event, $beginDate, $endDate, $description, $filePath]);


        if($event === 'awaria') {
            $query = "UPDATE equipments SET status='awaria' WHERE nrInv=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nrInv]);
        }
         
        $pdo = null;
        $stmt = null;

        $_SESSION['success'] = true;
        unset($_SESSION['formData']);

        header("Location: ../public/homepage.php");
        exit();
}

function delEvent($pdo,$id) {
    // Pobranie ścieżki pliku zdjęcia przed usunięciem rekordu
    $query = "SELECT file FROM equip_events WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $filePath = $stmt->fetchColumn();

    
    $query = "DELETE FROM equip_events WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    // Usunięcie pliku z folderu, jeśli istnieje
    if ($filePath && file_exists($filePath)) {
        unlink($filePath);
    }

    $pdo = null;
    $stmt = null;

    $_SESSION['success'] = true;
    unset($_SESSION['formData']);

    header("Location: ../public/homepage.php");
    exit();
}