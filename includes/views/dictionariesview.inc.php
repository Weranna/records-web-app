<?php
declare(strict_types=1);

require_once '../includes/config/config.php';
require_once '../includes/config/dbh.inc.php';

$tableMapping = [
    'Urządzenia' => 'devices',
    'Producenci' => 'manufacturers',
    'Dostawcy' => 'suppliers',
    'Lokalizacje' => 'locations',
    'Statusy' => 'statuses',
    'Użytkownicy' => 'users',
    'Zdarzenia' => 'events'
];

$columnHeaders = [
    'devices' => [
        'id' => 'ID',
        'name' => 'Nazwa'
    ],
    'manufacturers' => [
        'id' => 'ID',
        'name' => 'Nazwa'
    ],
    'suppliers' => [
        'id' => 'ID',
        'name' => 'Nazwa',
        'address' => 'Adres',
        'phone' => 'Telefon',
        'email' => 'Email'
    ],
    'locations' => [
        'id' => 'ID',
        'name' => 'Nazwa'
    ],
    'statuses' => [
        'id' => 'ID',
        'name' => 'Nazwa'
    ],
    'users' => [
        'id' => 'ID',
        'login' => 'Login',
        'user_group' => 'Grupa użytkowników',
        'email' => 'Email',
        'location' => 'Lokalizacja'
    ],
    'events' => [
        'id' => 'ID',
        'name' => 'Nazwa'
    ]
];

// Ustawienie tabeli z URL
$table = '';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['table'])) {
        $tableName = $_GET['table'];
        $table = $tableName;
    }
} else if ($_SERVER["REQUEST_METHOD"] === 'POST') { 
    $redirectPage = $_POST["redirect"] ?? 'homepage.php';
    unset($_SESSION['formData']);
    header("Location: $redirectPage");
    exit();
}

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header('Location: homepage.php');
    exit();
}

// Funkcja do wyświetlania tabeli
function displayTable(PDO $pdo, string $tableName, array $headers): void {
    $sql = "SELECT * FROM $tableName";
    try {
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h1>" . htmlspecialchars(array_search($tableName, $GLOBALS['tableMapping'])) . "</h1>
            <div class='button-container'>
                <button class='addPopupBtn' data-table='" . htmlspecialchars($tableName) . "'>Dodaj</button>
            </div>";

        if ($result && count($result) > 0) {
            echo "<table><tr>";

            foreach ($headers[$tableName] as $dbColumn => $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "<th>Opcje</th></tr>";

            foreach ($result as $row) {
                echo "<tr>";
                foreach ($headers[$tableName] as $dbColumn => $header) {
                    echo "<td>" . ($row[$dbColumn] ?? '') . "</td>";
                }
                echo "<td> <button class='editPopupBtn' data-table='" . htmlspecialchars($tableName) . "' data-id='" . $row['id'] . "'>Edytuj</button>
                    <br><button class='delPopupBtn' data-table='" . htmlspecialchars($tableName) . "' data-id='" . $row['id'] . "'>Usuń</button></td></tr>";
            }
            echo "</table>";
        } else {
            echo "Brak danych.";
        }
    } catch (PDOException $e) {
        echo "Query failed: " . htmlspecialchars($e->getMessage());
    }
}

if (isset($_SESSION['success'])) {
    echo '<div class="message success show"><p>Sukces<p></div>';
    unset($_SESSION['success']);
}