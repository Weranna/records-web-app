<?php 
require_once '../includes/models/homepagemodel.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $redirectPage = $_POST["redirect"] ?? 'homepage.php';
    unset($_SESSION['formData']);
    unset($_SESSION['errors']);
    header("Location: $redirectPage");
}

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$userType = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ewidencja sprzętów</title>
    <link rel="stylesheet" href="assets/css/list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>
<script src="assets/scripts/popupEquipDelete.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }
});
</script>
<div id="popup" class="popup">
        <div class="popup-content">
            <p id="popupMessage"></p>
            <div class="popupButtons">
                <button id="yesBtn">Tak</button>
                <button id="noBtn">Nie</button>
            </div>
        </div>
</div>
<div class="nav-container">
    <form method="post" class="navForm">
        <input type="submit" name="action" value="Sprzęty" class="navButton active">
    </form>
<?php if ($userType === 'admin'): ?>
    <form method="post" class="navForm">
        <input type="submit" name="action" value="Słowniki" class="navButton">
        <input type="hidden" name="redirect" value="dictionaries.php">
    </form>
        <?php endif; ?>
        <div class="user-menu">
            <button id="accountButton" class="navButton">Konto</button>
            <div id="accountPopup" class="hidden">
                <div class="user-popup-content">
                    <div class="user-info">
                        <strong>Użytkownik:</strong> <?php echo htmlspecialchars($_SESSION['login']); ?>
                    </div>
                    <div class="user-info">
                        <strong>Lokalizacja:</strong> <?php echo htmlspecialchars($_SESSION['user_location']); ?>
                    </div>
                    <form action="../includes/logouth.inc.php" method="post" class="userForm">
                        <button type="submit">Wyloguj</button>
                    </form>
                </div>
            </div>
        </div>

</div>
<div class="filter-container">
    <form class="filter" action="" method="GET">
        <div class="filter-element">
            <label for="deviceSelect">Urządzenie:</label>    
            <select id="deviceSelect" name="device">
                <option value="none" <?php echo (($_GET['device'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
                <?php foreach ($devices as $device): ?>
                    <option value="<?php echo htmlspecialchars($device['name']); ?>" <?php echo (($_GET['device'] ?? '') == $device['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($device['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-element">
            <label for="manufacturerSelect">Producent:</label>    
            <select id="manufacturerSelect" name="manufacturer">
                <option value="none" <?php echo (($_GET['manufacturer'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
                <?php foreach ($manufacturers as $manufacturer): ?>
                    <option value="<?php echo htmlspecialchars($manufacturer['name']); ?>" <?php echo (($_GET['manufacturer'] ?? '') == $manufacturer['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($manufacturer['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($userType === 'admin'): ?>
        <div class="filter-element">
            <label for="locationSelect">Lokalizacja:</label>    
            <select id="locationSelect" name="location">
                <option value="none" <?php echo (($_GET['location'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo htmlspecialchars($location['name']); ?>" <?php echo (($_GET['location'] ?? '') == $location['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($location['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div class="filter-element">
            <label for="supplierSelect">Dostawca:</label>    
            <select id="supplierSelect" name="supplier">
                <option value="none" <?php echo (($_GET['supplier'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo htmlspecialchars($supplier['name']); ?>" <?php echo (($_GET['supplier'] ?? '') == $supplier['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($supplier['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-element">
            <label for="statusSelect">Status:</label>    
            <select id="statusSelect" name="status">
                <option value="none" <?php echo (($_GET['status'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo htmlspecialchars($status['name']); ?>" <?php echo (($_GET['status'] ?? '') == $status['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($status['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
                    
        <div class="filter-element">
            <label for="purchaseDate">Data zakupu:</label>    
            <input type="date" id="purchaseDate" name="purchaseDate" value="<?php echo htmlspecialchars($_GET['purchaseDate'] ?? ''); ?>">
        </div>

        <div class="filter-element">
            <label for="warrantyDate">Data gwarancji:</label>    
            <input type="date" id="warrantyDate" name="warrantyDate" value="<?php echo htmlspecialchars($_GET['warrantyDate'] ?? ''); ?>">
        </div>

        <div class="filter-element">
            <label for="reviewDate">Data przeglądu:</label>    
            <input type="date" id="reviewDate" name="reviewDate" value="<?php echo htmlspecialchars($_GET['reviewDate'] ?? ''); ?>">
        </div>
        <div class="button-container">
            <input type="submit" name="action" value="Filtruj" class="filter-button">
            <button type="button" id="resetBtn" class="filter-button">Resetuj</button>
        </div>
        </div>
    </form>
    <?php if ($userType === 'admin'): ?>
        <form method="post" class="buttons">
    <button type="submit" name="action">
    Dodaj sprzęt
        <i class="fas fa-plus"></i>
    </button>
    <input type="hidden" name="redirect" value="equipform.php">
</form>


        <?php endif; ?>
<table>
<tr>
    <th>Opcje</th>
    <th>
        <div class="header-container">
            <span class="header-text">Nr inw.</span>
            <div class="sort-icons">
                <?php 
                echo '<a href="' . $url . '&sortColumn=nrInv&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=nrInv&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>Nr ser.</th>
    <th>Urządzenie</th>
    <th>Producent</th>
    <th>
        <div class="header-container">
            <span class="header-text">Model</span>
            <div class="sort-icons">
                <?php
                echo '<a href="' . $url . '&sortColumn=model&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=model&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>Lokalizacja</th>
    <th>Dostawca</th>
    <th>
        <div class="header-container">
            <span class="header-text">Data zakupu</span>
            <div class="sort-icons">
                <?php
                echo '<a href="' . $url . '&sortColumn=purchaseDate&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=purchaseDate&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>
        <div class="header-container">
            <span class="header-text">Data gwarancji</span>
            <div class="sort-icons">
                <?php
                echo '<a href="' . $url . '&sortColumn=warrantyDate&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=warrantyDate&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>
        <div class="header-container">
            <span class="header-text">Data przeglądu</span>
            <div class="sort-icons">
                <?php
                echo '<a href="' . $url . '&sortColumn=reviewDate&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=reviewDate&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>
        <div class="header-container">
            <span class="header-text">Wartość brutto</span>
            <div class="sort-icons">
                <?php
                echo '<a href="' . $url . '&sortColumn=value&sortOrder=ASC"><img src="assets/icons/up-arrow.png" alt="Sortuj rosnąco" class="sort-icon"></a>';
                echo '<a href="' . $url . '&sortColumn=value&sortOrder=DESC"><img src="assets/icons/down-arrow.png" alt="Sortuj malejąco" class="sort-icon"></a>';
                ?>
            </div>
        </div>
    </th>
    <th>Status</th>
</tr>
    <?php require_once '../includes/views/homepageview.inc.php';?>
   </table>
   <script>
        document.querySelectorAll('.showEventsBtn').forEach(button => {
            button.addEventListener('click', function() {
                const nrInv = this.getAttribute('data-nrInv');
                const eventsRow = document.getElementById('events-row-' + nrInv);
                if (eventsRow.classList.contains('hidden')) {
                    eventsRow.classList.remove('hidden');
                } else {
                    eventsRow.classList.add('hidden');
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
        var accountButton = document.getElementById('accountButton');
        var accountPopup = document.getElementById('accountPopup');
        
        accountButton.addEventListener('click', function() {
            console.log('Popup before toggle:', accountPopup.classList);
            accountPopup.classList.toggle('hidden');
            console.log('Popup after toggle:', accountPopup.classList);
        });

        // Zamknij okienko, gdy użytkownik kliknie poza nim
        document.addEventListener('click', function(event) {
            if (!accountPopup.contains(event.target) && !accountButton.contains(event.target)) {
                accountPopup.classList.add('hidden');
            }
        });
    });
    document.querySelectorAll('.showFilesBtn').forEach(button => {
    button.addEventListener('click', function() {
        const nrInv = this.getAttribute('data-nrInv');
        const photosRow = document.getElementById(`photos-row-${nrInv}`);
        
        // Ukrywanie innych sekcji zdjęć
        document.querySelectorAll('.photos-row').forEach(row => {
            if (row !== photosRow) row.classList.add('hidden');
        });

        // Przełączanie widoczności sekcji zdjęć
        photosRow.classList.toggle('hidden');
    });
});
document.addEventListener('DOMContentLoaded', function() {
        // Pobierz wszystkie przyciski podglądu
        const buttons = document.querySelectorAll('#eventPhotoButton');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Pobranie eventId z atrybutu data-id
                const eventId = this.getAttribute('data-id');
                const photoRow = document.getElementById('photos-row-' + eventId);
                
                // Przełączanie widoczności
                if (photoRow.classList.contains('hidden')) {
                    photoRow.classList.remove('hidden');
                } else {
                    photoRow.classList.add('hidden');
                }
            });
        });
    });


    </script>
</body>
</html>
