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
    <title>Records Equipment</title>
    <link rel="stylesheet" href="assets/css/list.css">
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
<section class="wrapper-main">
<div id="popup" class="popup">
        <div class="popup-content">
            <p id="popupMessage"></p>
            <button id="yesBtn">Tak</button>
            <button id="noBtn">Nie</button>
        </div>
    </div>
<div class="button-container">
<?php if ($userType === 'admin'): ?>
    <form method="post" class="navButtonForm">
        <input type="submit" name="action" value="Słowniki" class="navButton">
        <input type="hidden" name="redirect" value="dictionaries.php">
    </form>
    <form method="post" class="navButtonForm">
            <input type="submit" name="action" value="Dodaj sprzęt" class="navButton">
            <input type="hidden" name="redirect" value="equipform.php">
        </form>
        <?php endif; ?>
        <form action="../includes/logouth.inc.php" method="post"  class="navButtonForm">
            <button class="navButton">Wyloguj się</button>
        </form>
</div>
<div class="filter-container">
<form class="filter" action="" method="GET">
    <label for="deviceSelect">Urządzenie:</label>    
    <select id="deviceSelect" name="device">
        <option value="none" <?php echo (($_GET['device'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
        <?php foreach ($devices as $device): ?>
            <option value="<?php echo htmlspecialchars($device['name']); ?>" <?php echo (($_GET['device'] ?? '') == $device['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($device['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="manufacturerSelect">Producent:</label>    
    <select id="manufacturerSelect" name="manufacturer">
        <option value="none" <?php echo (($_GET['manufacturer'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
        <?php foreach ($manufacturers as $manufacturer): ?>
            <option value="<?php echo htmlspecialchars($manufacturer['name']); ?>" <?php echo (($_GET['manufacturer'] ?? '') == $manufacturer['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($manufacturer['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if ($userType === 'admin'): ?>
    <label for="locationSelect">Lokalizacja:</label>    
    <select id="locationSelect" name="location">
        <option value="none" <?php echo (($_GET['location'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
        <?php foreach ($locations as $location): ?>
            <option value="<?php echo htmlspecialchars($location['name']); ?>" <?php echo (($_GET['location'] ?? '') == $location['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($location['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>
    <label for="supplierSelect">Dostawca:</label>    
    <select id="supplierSelect" name="supplier">
        <option value="none" <?php echo (($_GET['supplier'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
        <?php foreach ($suppliers as $supplier): ?>
            <option value="<?php echo htmlspecialchars($supplier['name']); ?>" <?php echo (($_GET['supplier'] ?? '') == $supplier['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($supplier['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="statusSelect">Status:</label>    
    <select id="statusSelect" name="status">
        <option value="none" <?php echo (($_GET['status'] ?? '') == 'none') ? 'selected' : ''; ?>>Dowolne</option>
        <?php foreach ($statuses as $status): ?>
            <option value="<?php echo htmlspecialchars($status['name']); ?>" <?php echo (($_GET['status'] ?? '') == $status['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($status['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="purchaseDate">Data zakupu:</label>    
    <input type="date" id="purchaseDate" name="purchaseDate" value="<?php echo htmlspecialchars($_GET['purchaseDate'] ?? ''); ?>">

    <label for="warrantyDate">Data gwarancji:</label>    
    <input type="date" id="warrantyDate" name="warrantyDate" value="<?php echo htmlspecialchars($_GET['warrantyDate'] ?? ''); ?>">

    <label for="reviewDate">Data przeglądu:</label>    
    <input type="date" id="reviewDate" name="reviewDate" value="<?php echo htmlspecialchars($_GET['reviewDate'] ?? ''); ?>">

    <input type="submit" name="action" value="Filtruj">
    <button type="button" id="resetBtn">Resetuj</button>
</form>

</div>
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
   </section>
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
    </script>
</body>
</html>
