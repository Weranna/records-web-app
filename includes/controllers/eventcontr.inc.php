<?php

declare(strict_types=1);

function isInputEmpty($event, $beginDate, $endDate, $description) {
    $errors = [];
    if ($event === 'none') $errors[] = 'Zdarzenie jest wymagane.';
    if (empty($beginDate)) $errors[] = 'Data rozpoczęcia jest wymagana.';    
    if (empty($endDate)) $errors[] = 'Data zakończenia jest wymagana.';
    if (empty($description)) $errors[] = 'Opis jest wymagany';

    return $errors;
}

function handleFile() {
    $filePaths = [];
    
    if (isset($_FILES['files']) && $_FILES['files']['error'][0] == UPLOAD_ERR_OK) {
        $maxFileSize = 5 * 1024 * 1024; // Maksymalny rozmiar: 5 MB
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ];

        // Iteracja przez przesłane pliki
        foreach ($_FILES['files']['tmp_name'] as $key => $fileTmpPath) {
            $mimeType = mime_content_type($fileTmpPath);
            $fileSize = $_FILES['files']['size'][$key];
            $fileName = $_FILES['files']['name'][$key];
            
            // Sprawdzenie rozmiaru i typu MIME
            if (!in_array($mimeType, $allowedMimeTypes) || $fileSize > $maxFileSize) {
                $_SESSION['errors'] = 'Niepoprawny typ lub zbyt duży plik: ' . htmlspecialchars($fileName);
                header("Location: ../public/eventform.php");
                exit();
            }

            // Tworzenie unikalnej nazwy pliku (z oryginalną nazwą)
            $uploadDir = '../public/assets/uploads/';
            // Użycie czasu jako prefiksu
            $timestamp = time();
            $newFileName = $timestamp . '_' . basename($fileName);
            $destPath = $uploadDir . $newFileName;

            // Przeniesienie pliku do katalogu docelowego
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                $_SESSION['errors'] = 'Wystąpił problem podczas przesyłania pliku: ' . htmlspecialchars($fileName);
                header("Location: ../public/eventform.php");
                exit();
            }

            $filePaths[] = $destPath; 
        }
    }
    return $filePaths;
}
