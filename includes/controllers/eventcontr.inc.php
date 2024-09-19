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
        $fileName = $_FILES['file']['name'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
    
        $uploadDir = '../public/assets/uploads/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Unikalna nazwa pliku
        $destPath = $uploadDir . $newFileName;
    
        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            $_SESSION['errors'] = 'Wystąpił problem podczas przesyłania pliku.';
            header("Location: ../public/eventform.php");
            exit();
        }
    
        $filePath = $destPath;
    } else {
        $filePath = null;
    }

    return $filePath;
}