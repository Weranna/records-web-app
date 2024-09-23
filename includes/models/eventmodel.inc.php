<?php
declare(strict_types=1);

function addEvent($pdo,$nrInv, $event, $beginDate, $endDate, $description, $filePaths) {
    $query = "INSERT INTO equip_events (equipId, name, beginDate, endDate, description)
                  VALUES (?, ?, ?, ?, ?);";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv, $event, $beginDate, $endDate, $description]);

        $itemId = $pdo->lastInsertId();

        if($event === 'awaria') {
            $query = "UPDATE equipments SET status='awaria' WHERE nrInv=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nrInv]);
        }

        // Wstawianie zdjęć do tabeli files
        if (!empty($filePaths)) {
            $fileQuery = "INSERT INTO files_events (eventId, file_path) VALUES (?, ?)";
            $fileStmt = $pdo->prepare($fileQuery);

            foreach ($filePaths as $filePath) {
                $fileStmt->execute([$itemId, $filePath]);
            }
        } 
         
        $pdo = null;
        $stmt = null;
        $fileStmt = null;

        $_SESSION['success'] = true;
        unset($_SESSION['formData']);

        header("Location: ../public/homepage.php");
        exit();
}

function delEvent($pdo,$id) {

    // Pobranie ścieżki pliku zdjęcia przed usunięciem rekordu
    $query = "SELECT file_path FROM files_events WHERE eventId = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $imagePaths = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $query = "DELETE FROM equip_events WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    /// Usunięcie plików zdjęć z folderu
    foreach ($imagePaths as $imagePath) {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Usunięcie rekordów z tabeli files
    $query = "DELETE FROM files_events WHERE eventId = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    $pdo = null;
    $stmt = null;

    $_SESSION['success'] = true;
    unset($_SESSION['formData']);

    header("Location: ../public/homepage.php");
    exit();
}