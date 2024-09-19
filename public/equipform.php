<?php
require_once '../includes/models/equipformmodel.inc.php';
require_once '../includes/views/equipformview.inc.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records Equipment</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
<section class="wrapper-form">
    <p>DODAJ SPRZĘT</p>
    <form method="post" class="backButtonForm">
        <input type="submit" name="redirect" value="Wróć" class="backButton">
    </form>
    <form action="../includes/addequiph.inc.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <label for="serialNumber">Numer seryjny</label>
        <input type="text" id="serialNumber" name="serNumber" placeholder="Numer seryjny" value="<?php echo htmlspecialchars($_SESSION['formData']['serNumber'] ?? ''); ?>">
        <br>
        <label for="device">Urządzenie</label>
        <select name="device" id="device">
            <option value="none" <?php echo (($_SESSION['formData']['device'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz urządzenie</option>
                <?php foreach ($devices as $device): ?>
                    <option value="<?php echo htmlspecialchars($device['name']); ?>" <?php echo (($_SESSION['formData']['device'] ?? '') == $device['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($device['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="manufacturer">Producent</label>
        <select name="manufacturer" id="manufacturer">
            <option value="none" <?php echo (($_SESSION['formData']['manufacturer'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz producenta</option>
                <?php foreach ($manufacturers as $manufacturer): ?>
                    <option value="<?php echo htmlspecialchars($manufacturer['name']); ?>" <?php echo (($_SESSION['formData']['manufacturer'] ?? '') == $manufacturer['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($manufacturer['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="model">Model</label>
        <input type="text" id="model" name="model" placeholder="Model" value="<?php echo htmlspecialchars($_SESSION['formData']['model'] ?? ''); ?>">
        <br>
        <label for="location">Lokalizacja</label>
        <select name="location" id="location">
            <option value="none" <?php echo (($_SESSION['formData']['location'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz lokalizacje</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo htmlspecialchars($location['name']); ?>" <?php echo (($_SESSION['formData']['location'] ?? '') == $location['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($location['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="supplier">Dostawca</label>
        <select name="supplier" id="supplier">
            <option value="none" <?php echo (($_SESSION['formData']['supplier'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz dostawcę</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo htmlspecialchars($supplier['name']); ?>" <?php echo (($_SESSION['formData']['supplier'] ?? '') == $supplier['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($supplier['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="purchaseDate">Data zakupu</label>
        <input type="date" id="purchaseDate" name="purchaseDate" value="<?php echo htmlspecialchars($_SESSION['formData']['purchaseDate'] ?? ''); ?>">
        <br>
        <label for="warrantyDate">Data gwarancji</label>
        <input type="date" id="warrantyDate" name="warrantyDate" value="<?php echo htmlspecialchars($_SESSION['formData']['warrantyDate'] ?? ''); ?>">
        <br>
        <label for="reviewDate">Data przeglądu</label>
        <input type="date" id="reviewDate" name="reviewDate" value="<?php echo htmlspecialchars($_SESSION['formData']['reviewDate'] ?? ''); ?>">
        <br>
        <label for="value">Wartość brutto (zł)</label>
        <input type="number" id="value" name="value" step="0.01" min="0" placeholder="Wartość brutto" value="<?php echo htmlspecialchars($_SESSION['formData']['value'] ?? ''); ?>">
        <br>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="none" <?php echo (($_SESSION['formData']['status'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz status</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo htmlspecialchars($status['name']); ?>" <?php echo (($_SESSION['formData']['status'] ?? '') == $status['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($status['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="photo">Zdjęcie</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        <br>
        <label for="notes">Uwagi</label>
        <textarea name="notes" id="notes" placeholder="Uwagi"><?php echo htmlspecialchars($_SESSION['formData']['notes'] ?? ''); ?></textarea>
        <br>
        <button type="submit" value="submit">Dodaj sprzęt</button>
    </form>
</section>
</body>
</html>
