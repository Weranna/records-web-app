<?php

declare(strict_types=1);

function isInputEmpty($event,$beginDate,$endDate,$description) {

    $errors = [];
    if ($event === 'none') $errors[] = 'Zdarzenie jest wymagane.';
    if (empty($beginDate)) $errors[] = 'Data rozpoczęcia jest wymagana.';    
    if (empty($endDate)) $errors[] = 'Data zakończenia jest wymagana.';
    if(empty($description)) $errors[] = 'Opis jest wymagany';

    return $errors;

}

function handleFile() {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        
        // Sprawdzenie typu MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmpPath);
        finfo_close($finfo);

        $maxFileSize = 5 * 1024 * 1024; // Maksymalny rozmiar: 5 MB
        $allowedMimeTypes = [
            'application/pdf',  // Pliki PDF
            'application/msword',  // Word
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  // Word (DOCX)
            'image/jpeg',  // Obrazy JPEG
            'image/png'  // Obrazy PNG
        ];

        // Sprawdzenie rozmiaru i typu MIME
        if (!in_array($mimeType, $allowedMimeTypes) || $_FILES['file']['size'] > $maxFileSize) {
            $_SESSION['errors'] = 'Niepoprawny typ lub zbyt duży plik.';
            header("Location: ../public/eventform.php");
            exit();
        }

        // Tworzenie unikalnej nazwy pliku
        $fileName = $_FILES['file']['name'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $uploadDir = '../public/assets/uploads/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        // Przeniesienie pliku do katalogu docelowego
        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            $_SESSION['errors'] = 'Wystąpił problem podczas przesyłania pliku.';
            header("Location: ../public/eventform.php");
            exit();
        }

        return $destPath;
    } else {
        return null;
    }
}

