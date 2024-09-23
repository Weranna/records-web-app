<?php

declare(strict_types=1);

function isInputEmpty($serNumber,$device,$manufacturer,$model,$location,$supplier,$purchaseDate,$warrantyDate,$reviewDate,$value,$status) {

    $errors = [];
    
    if (empty($serNumber)) $errors[] = 'Numer seryjny jest wymagany.';
    if ($device == 'none') $errors[] = 'Urządzenie jest wymagane.';
    if ($manufacturer == 'none') $errors[] = 'Producent jest wymagany.';
    if (empty($model)) $errors[] = 'Model jest wymagany.';
    if ($location == 'none') $errors[] = 'Lokalizacja jest wymagana.';
    if ($supplier == 'none') $errors[] = 'Dostawca jest wymagany.';
    if (empty($purchaseDate)) $errors[] = 'Data zakupu jest wymagana.';
    if (empty($warrantyDate)) $errors[] = 'Data gwarancji jest wymagana.';
    if (empty($reviewDate)) $errors[] = 'Data przeglądu jest wymagana.';
    if (empty($value)) $errors[] = 'Wartość brutto jest wymagana.';
    if ($status == 'none') $errors[] = 'Status jest wymagany.';

    return $errors;

}

function handlePhoto(): array {
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

                // Tworzenie unikalnej nazwy pliku (z oryginalną nazwą)
                $uploadDir = '../public/assets/uploads/';
                // Użycie czasu jako prefiksu
                $timestamp = time();
                $newFileName = $timestamp . '_' . basename($fileName);
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $filePaths[] = $destPath; // Dodaj ścieżkę do tablicy
                } else {
                    $_SESSION['errors'][] = 'Wystąpił problem podczas przesyłania pliku: ' . $fileName;
                }
            }
        }
    }

    return $filePaths;
}
