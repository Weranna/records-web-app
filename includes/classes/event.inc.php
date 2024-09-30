<?php
declare(strict_types=1);

class Event {
    private string $event;
    private string $beginDate;
    private string $endDate;
    private string $description;
    private array $filePaths = [];

    public function __construct(string $event='', string $beginDate='', string $endDate='', string $description='') {
        $this->event = htmlspecialchars($event);
        $this->beginDate = htmlspecialchars($beginDate);
        $this->endDate = htmlspecialchars($endDate);
        $this->description = htmlspecialchars($description);
    }

    public function validate(): array {
        $errors = [];
        if (empty($this->event)) {
            $errors[] = 'Zdarzenie jest wymagane.';
        }
        if (empty($this->beginDate)) {
            $errors[] = 'Data rozpoczęcia jest wymagana.';
        }
        if (empty($this->endDate)) {
            $errors[] = 'Data zakończenia jest wymagana.';
        }
        if (empty($this->description)) {
            $errors[] = 'Opis jest wymagany.';
        }
        return $errors;
    }

    public function handleFileUpload(): void {
        if (isset($_FILES['files']) && $_FILES['files']['error'][0] == UPLOAD_ERR_OK) {
            $maxFileSize = 5 * 1024 * 1024; // Maksymalny rozmiar: 5 MB
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/png'
            ];

            foreach ($_FILES['files']['tmp_name'] as $key => $fileTmpPath) {
                $mimeType = mime_content_type($fileTmpPath);
                $fileSize = $_FILES['files']['size'][$key];
                $fileName = $_FILES['files']['name'][$key];

                // Sprawdzenie rozmiaru i typu MIME
                if (!in_array($mimeType, $allowedMimeTypes) || $fileSize > $maxFileSize) {
                    throw new Exception('Niepoprawny typ lub zbyt duży plik: ' . htmlspecialchars($fileName));
                }

                // Tworzenie unikalnej nazwy pliku
                $uploadDir = '../public/assets/uploads/';
                $timestamp = time();
                $newFileName = $timestamp . '_' . basename($fileName);
                $destPath = $uploadDir . $newFileName;

                if (!move_uploaded_file($fileTmpPath, $destPath)) {
                    throw new Exception('Wystąpił problem podczas przesyłania pliku: ' . htmlspecialchars($fileName));
                }

                $this->filePaths[] = $destPath;
            }
        }
    }

    public function addToDatabase(PDO $pdo, String $nrInv): void {
        $query = "INSERT INTO equip_events (equipId, name, beginDate, endDate, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv, $this->event, $this->beginDate, $this->endDate, $this->description]);

        $itemId = $pdo->lastInsertId();
        
        if ($this->event === 'awaria') {
            $query = "UPDATE equipments SET status='awaria' WHERE nrInv=?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nrInv]);
        }

        if (!empty($this->filePaths)) {
            $fileQuery = "INSERT INTO files_events (eventId, file_path) VALUES (?, ?)";
            $fileStmt = $pdo->prepare($fileQuery);
            foreach ($this->filePaths as $filePath) {
                $fileStmt->execute([$itemId, $filePath]);
            }
        }
    }

    public function deleteFromDatabase(PDO $pdo, int $id): void {
        try {
   
            $filePaths = $this->getFilePaths($pdo, $id);
    
            $query = "DELETE FROM equip_events WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$id]);

            $this->deleteFiles($filePaths);
           
            $this->deleteFilesFromDatabase($pdo, $id);

            $_SESSION['success'] = true;
        } catch (PDOException $e) {
            $_SESSION['errors'][] = $e->getMessage();
        }
    }

    private function getFilePaths(PDO $pdo, int $id): array {
        $query = "SELECT file_path FROM files_events WHERE eventId = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function deleteFiles(array $filePaths): void {
        foreach ($filePaths as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function deleteFilesFromDatabase(PDO $pdo, int $id): void {
        $query = "DELETE FROM files_events WHERE eventId = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
    }
}
