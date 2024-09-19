<?php
declare(strict_types=1);
function validateForm(array $data, array $columns): array
{
    $errors = [];
    $values = [];
    $inputValues = [];

    foreach ($columns as $column) {
        if (empty($data[$column])) {
                $errors[] = "Uzupełnij puste pola";
                break;
            } else {
            $safeValue = htmlspecialchars($data[$column]);
            $values[$column] = $safeValue;
            $inputValues[$column] = $data[$column];
        }
    }

    return ['errors' => $errors, 'values' => $values, 'inputValues' => $inputValues];
}

function hashPwd(array $values, array $columns): array {
    if (isset($values[array_search('pwd', $columns)])) {
        $options = ['cost' => 12];
        $values[array_search('pwd', $columns)] = password_hash($values[array_search('pwd', $columns)], PASSWORD_DEFAULT, $options);
    }
    return $values;
}

function isEmailInvalid($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        return true;
    } else {
        return false;
    }
}

function handleDatabaseError(PDOException $e, array $tableMapping, array $validationResult): void {
    if ($e->getCode() === '23000') {
        $errorInfo = $e->errorInfo[2];
        $existingValue = '';

        foreach ($tableMapping as $column) {
            if (strpos($errorInfo, $column) !== false) {
                $existingValue = $validationResult['inputValues'][$column];
                break;
            }
        }

        $_SESSION['errors'][] = "Taka pozycja już istnieje: \"$existingValue\"";
    } else {
        $_SESSION['errors'][] = "Wystąpił błąd: " . $e->getMessage();
    }
    echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
    exit();
}

function handleDatabaseErrorOnDelete(PDOException $e): void {
    if ($e->getCode() === '23000') {
        $_SESSION['errors'][] = "Element jest używany nie można go usunąć";
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }
}

function  handleDatabaseErrorOnEdit(PDOException $e, array $tableMapping, array $validationResult): void { 
    if ($e->getCode() === '23000') {
        $errorInfo = $e->errorInfo[2];
        $existingValue = '';

        // Sprawdzenie, czy komunikat błędu zawiera odniesienia do kluczy obcych
        if (strpos($errorInfo, 'FOREIGN KEY') !== false) {
            $_SESSION['errors'][] = 'Nie można zmienić tej wartości, ponieważ jest używana w innych tabelach.';
        } else {
            foreach ($tableMapping as $column) {
                if (strpos($errorInfo, $column) !== false) {
                    $existingValue = $validationResult['inputValues'][$column] ?? '';
                    break;
                }
            }
            $_SESSION['errors'][] = "Taka pozycja już istnieje: \"$existingValue\"";
        }
    } else {
        $_SESSION['errors'][] = "Wystąpił błąd: " . $e->getMessage();
    }
    echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
    exit();
}