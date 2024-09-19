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

function handlePhoto() {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
    
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['errors'] = 'Nieprawidłowy format pliku. Dozwolone są tylko pliki jpg, jpeg, png, gif.';
            header("Location: ../public/equipform.php");
            exit();
        }
    
        $uploadDir = '../public/assets/uploads/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Unikalna nazwa pliku
        $destPath = $uploadDir . $newFileName;
    
        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            $_SESSION['errors'] = 'Wystąpił problem podczas przesyłania pliku.';
            header("Location: ../public/equipform.php");
            exit();
        }
    
        $filePath = $destPath;
    } else {
        $filePath = null;
    }

    return $filePath;
}