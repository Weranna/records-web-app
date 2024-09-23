<?php
declare(strict_types=1);
require_once '../includes/config/config.php';
require_once '../includes/config/dbh.inc.php';
require_once '../includes/models/homepagemodel.inc.php';

if ($stmt->rowCount() > 0) {
    foreach ($results as $row) {
        $purchaseDate = new DateTime($row["purchaseDate"]);
        $formattedPurchaseDate = $purchaseDate->format('d.m.Y');
        $warrantyDate = new DateTime($row["warrantyDate"]);
        $formattedWarrantyDate = $warrantyDate->format('d.m.Y');
        $reviewDate = new DateTime($row["reviewDate"]);
        $formattedReviewDate = $reviewDate->format('d.m.Y');
        
        // Pobierz zdarzenia dla danego nrInv
        $eventSql = "SELECT id, name, beginDate, endDate, description FROM equip_events WHERE equipId = :nrInv";
        $eventStmt = $pdo->prepare($eventSql);
        $eventStmt->execute([':nrInv' => $row["nrInv"]]);
        $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

         // Pobieranie zdjęć
         $photoSql = "SELECT file_path FROM files WHERE nrInv = :nrInv";
         $photoStmt = $pdo->prepare($photoSql);
         $photoStmt->execute([':nrInv' => $row["nrInv"]]);
         $photos = $photoStmt->fetchAll(PDO::FETCH_COLUMN);

        // Wyświetlanie danych sprzętu
        echo "<tr id='device-" . $row["nrInv"] . "'>
        <td class='buttons'>";

        if ($userType === 'admin') {
            echo "<a href='equipformedit.php?nrInv=" . $row["nrInv"] . "'><button>Edytuj</button></a>";
        }

        echo "<button class='showEventsBtn' data-nrInv='" . $row["nrInv"] . "'>Zdarzenia</button>
        <button class='showFilesBtn' data-nrInv='" . $row["nrInv"] . "'>Podgląd</button>";

        if ($userType === 'admin') {
            echo "<button id='delButton' class='showPopupBtn' data-nrInv='" . $row["nrInv"] . "'>Usuń</button>";
        }

        echo "</td><td>" . $row["nrInv"] . "</td><td class='fixed-td'>" . 
        $row["nrSer"] . "</td><td class='fixed-td'>" . 
        $row["device"] . "</td><td class='fixed-td'>" . 
        $row["manufacturer"] . "</td><td class='fixed-td'>" . 
        $row["model"] . "</td><td class='fixed-td'>" . 
        $row["location"] . "</td><td class='fixed-td'>" . 
        $row["supplier"] . "</td><td class='fixed-td'>" . 
        $formattedPurchaseDate . "</td><td class='fixed-td'>" . 
        $formattedWarrantyDate . "</td><td class='fixed-td'>" . 
        $formattedReviewDate . "</td><td class='fixed-td'>" . 
        $row["value"] . "zł</td><td class='fixed-td'>" . 
        $row["status"] . "</td></tr>";

        // Dodanie wiersza dla zdjęć
        echo "<tr id='photos-row-" . $row["nrInv"] . "' class='photos-row hidden'>
        <td colspan='14'>
            <div class='photos-section'>";
            if ($photos) {
                echo "<p>Zdjęcia:</p><div class='photos'>";
                foreach ($photos as $photo) {
                    echo "<img src='" . htmlspecialchars($photo) . "' alt='Zdjęcie sprzętu' class='equipment-photo'>";
                }
                echo "</div>";
            } else {
                echo "<p>Brak zdjęć.</p>";
            }
            echo "</div>
            <div>";
            if ($row['notes']) {
               echo "<p>Uwagi:</p>
               <div class='notes'>" . $row["notes"] . "</div>";
            } else {
                echo "<p>Brak uwag.</p>";
            }
            echo "</div></td></tr>";

        // Dodanie wiersza dla zdarzeń
        echo "<tr id='events-row-" . $row["nrInv"] . "' class='events-row hidden'>
                <td colspan='14'>
                <table class='events-table'>
                    <tr>
                        <th>Nazwa</th>
                        <th>Data rozpoczęcia</th>
                        <th>Data zakończenia</th>";

        if ($userType === 'admin' && $events) {
            echo "<th>Opcje</th>";
        }

        echo "   </tr>";

        
        if ($events) {
            foreach ($events as $event) {
                $beginDate = new DateTime($event["beginDate"]);
                $formattedBeginDate = $beginDate->format('d.m.Y');
                $endDate = new DateTime($event["endDate"]);
                $formattedEndDate = $endDate->format('d.m.Y');

                // Pobieranie plików
                $fileSql = "SELECT file_path FROM files_events WHERE eventId = :id";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->execute([':id' => $event["id"]]);
                $files = $fileStmt->fetchAll(PDO::FETCH_COLUMN);

                echo "<tr>
                    <td>" . htmlspecialchars($event["name"]) . "</td>
                    <td>$formattedBeginDate</td>
                    <td>$formattedEndDate</td>";
                    
                if ($userType === 'admin') {
                    echo "<td class='buttons'><button id='delButton' class='showPopupBtn' data-id='" . $event["id"] . "'>Usuń</button>";
                }
                    echo "<button id='eventPhotoButton' data-id='" . $event["id"] . "'>Podgląd</button></td></tr>";
                    
                    // Dodanie wiersza dla zdjęć
                    echo "<tr id='photos-row-" . $event["id"] . "' class='photos-row hidden'>
                    <td colspan='14'>
                        <div class='photos-section'>";
                        if ($files) {
                            echo "<p>Pliki:</p><div class='files'>"; // Zmiana z 'photos' na 'files'
                            foreach ($files as $file) {
                                // Zakładam, że plik jest obrazem jeśli jego rozszerzenie to jpg, png itp.
                                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo "<img src='" . htmlspecialchars($file) . "' alt='Zdjęcie sprzętu' class='equipment-photo'>";
                                } else {
                                    // Wyświetl ikonę pliku
                                    echo "<a href='" . htmlspecialchars($file) . "' target='_blank'>
                                        <i class='fas fa-file'></i> " . htmlspecialchars(basename($file)) . "
                                    </a>"; // Upewnij się, że masz Font Awesome załadowane
                                }
                            }
                            echo "</div>";
                        } else {
                            echo "<p>Brak plików.</p>";
                        }
                        echo "</div>
                        <div>";
                        if ($row['notes']) {
                            echo "<p>Opis:</p>
                            <div class='notes'>" . $event["description"] . "</div>";
                        }
                        echo "</div></td></tr>";

            }
        } else {
            echo "<tr><td colspan='5'>Brak zdarzeń</td></tr>";
        }

        echo "</table>";

        echo "<form method='post' action='"; 

        if ($userType === 'admin') {
            echo "eventform.php";
        } else {
            echo "reportform.php";
        }

        unset($_SESSION['errors']);

        echo "' class='buttons'> <input type='hidden' name='nrInv' value='" . $row['nrInv'] . "'>";

        if ($userType === 'admin') {
            echo "<input type='submit' name='action' value='Dodaj zdarzenie' class='buttons'>";
        } else {
            echo "<input type='submit' name='action' value='Zgłoś awarię' class='buttons' id='delButton'>";
        }

        echo "</form></td></tr>";
            
    }
} else {
    echo "<tr><td colspan='14'>Brak sprzętów w bazie danych</td></tr>";
}

if (isset($_SESSION['success'])) {
    echo '<div class="message success show"><p>Sukces<p></div>';
    unset($_SESSION['success']);
}


$pdo = null;
$stmt = null;