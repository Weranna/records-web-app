<?php
declare(strict_types=1);

class Equipment {
    private string $serNumber;
    private string $device;
    private string $manufacturer;
    private string $model;
    private string $location;
    private string $supplier;
    private string $purchaseDate;
    private string $warrantyDate;
    private string $reviewDate;
    private float $value;
    private string $status;
    private string $notes;

    public function setDetails(
        string $serNumber,
        string $device,
        string $manufacturer,
        string $model,
        string $location,
        string $supplier,
        string $purchaseDate,
        string $warrantyDate,
        string $reviewDate,
        float $value,
        string $status,
        string $notes
    ): void {
        $this->serNumber = $serNumber;
        $this->device = $device;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->purchaseDate = $purchaseDate;
        $this->warrantyDate = $warrantyDate;
        $this->reviewDate = $reviewDate;
        $this->value = $value;
        $this->status = $status;
        $this->notes = $notes;
    }

    public function addEquip(array $filePaths, PDO $pdo): void {
        $query = "INSERT INTO equipments (nrSer, device, manufacturer, model, location, supplier, purchaseDate, warrantyDate, reviewDate, value, status, notes)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            $this->serNumber,
            $this->device,
            $this->manufacturer,
            $this->model,
            $this->location,
            $this->supplier,
            $this->purchaseDate,
            $this->warrantyDate,
            $this->reviewDate,
            $this->value,
            $this->status,
            $this->notes
        ]);

        $itemId = $pdo->lastInsertId();

        if (!empty($filePaths)) {
            $fileQuery = "INSERT INTO files (nrInv, file_path) VALUES (?, ?)";
            $fileStmt = $pdo->prepare($fileQuery);
            foreach ($filePaths as $filePath) {
                $fileStmt->execute([$itemId, $filePath]);
            }
        }

        unset($_SESSION['formData']);
        $_SESSION['success'] = true;

        header("Location: ../public/homepage.php");
        exit();
    }

    public function delEquip(string $nrInv, PDO $pdo): void {

        $query = "SELECT file_path FROM files WHERE nrInv = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv]);
        $imagePaths = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $query = "DELETE FROM equip_events WHERE equipId = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv]);

        $query = "DELETE FROM equipments WHERE nrInv = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv]);

        foreach ($imagePaths as $imagePath) {
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $query = "DELETE FROM files WHERE nrInv = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nrInv]);

        $_SESSION['success'] = true;

        header("Location: ../public/homepage.php");
        exit();
    }

    public function editEquip(string $nrInv, PDO $pdo): void {
        $query = "UPDATE equipments 
                  SET nrSer = ?, device = ?, manufacturer = ?, model = ?, location = ?, supplier = ?, purchaseDate = ?, warrantyDate = ?, reviewDate = ?, value = ?, status = ?, notes = ?
                  WHERE nrInv = ?";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            $this->serNumber,
            $this->device,
            $this->manufacturer,
            $this->model,
            $this->location,
            $this->supplier,
            $this->purchaseDate,
            $this->warrantyDate,
            $this->reviewDate,
            $this->value,
            $this->status,
            $this->notes,
            $nrInv
        ]);

        unset($_SESSION['formData']);
        $_SESSION['success'] = true;

        header("Location: ../public/homepage.php");
        exit();
    }

    public function isInputEmpty($serNumber, $device, $manufacturer, $model, $location, $supplier, $purchaseDate, $warrantyDate, $reviewDate, $value, $status): array {
        $errors = [];

        if (empty($serNumber)) $errors[] = 'Numer seryjny jest wymagany.';
        if ($device === 'none') $errors[] = 'Urządzenie jest wymagane.';
        if ($manufacturer === 'none') $errors[] = 'Producent jest wymagany.';
        if (empty($model)) $errors[] = 'Model jest wymagany.';
        if ($location === 'none') $errors[] = 'Lokalizacja jest wymagana.';
        if ($supplier === 'none') $errors[] = 'Dostawca jest wymagany.';
        if (empty($purchaseDate)) $errors[] = 'Data zakupu jest wymagana.';
        if (empty($warrantyDate)) $errors[] = 'Data gwarancji jest wymagana.';
        if (empty($reviewDate)) $errors[] = 'Data przeglądu jest wymagana.';
        if (empty($value)) $errors[] = 'Wartość brutto jest wymagana.';
        if ($status === 'none') $errors[] = 'Status jest wymagany.';

        return $errors;
    }

    public function handlePhoto(): array {
        $filePaths = [];

        if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $files = $_FILES['photos'];

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $files['tmp_name'][$i];
                    $fileName = $files['name'][$i];
                    $fileNameCmps = explode('.', $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $_SESSION['errors'][] = 'Nieprawidłowy format pliku: ' . $fileName;
                        continue;
                    }

                    $uploadDir = '../public/assets/uploads/';
                    $timestamp = time();
                    $newFileName = $timestamp . '_' . basename($fileName);
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $filePaths[] = $destPath;
                    } else {
                        $_SESSION['errors'][] = 'Wystąpił problem podczas przesyłania pliku: ' . $fileName;
                    }
                }
            }
        }

        return $filePaths;
    }
}
