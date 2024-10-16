<?php
require_once '../includes/views/eventformview.inc.php';
require_once '../includes/models/eventformmodel.inc.php';

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ewidencja sprzętów</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
<section class="wrapper-form">
    <p class="title">DODAJ ZDARZENIE</p>
    <button class="backButtonForm" onclick="window.location.href='homepage.php';">Wróć</button>
    <form class="mainForm" action="../includes/addeventh.inc.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="nrInv" value="<?php echo htmlspecialchars($_POST['nrInv'] ?? ''); ?>">
    <label for="event">Zdarzenie</label>
        <select name="event" id="event">
            <option value="none" <?php echo (($_SESSION['formData']['event'] ?? '') == 'none') ? 'selected' : ''; ?>>Wybierz zdarzenie</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo htmlspecialchars($event['name']); ?>" <?php echo (($_SESSION['formData']['event'] ?? '') == $event['name']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($event['name']); ?>
                </option>
        <?php endforeach; ?>
        </select>
        <br>
        <label for="beginDate">Data rozpoczęcia</label>
        <input type="date" id="beginDate" name="beginDate" value="<?php echo htmlspecialchars($_SESSION['formData']['beginDate'] ?? ''); ?>">
        <br>
        <label for="endDate">Data zakończenia</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($_SESSION['formData']['endDate'] ?? ''); ?>">
        <br>
        <label for="files">Załącznik</label>
        <input type="file" id="files" name="files[]" multiple>
        <br>
        <label for="description">Opis</label>
        <textarea name="description" id="description" placeholder="Opis"><?php echo htmlspecialchars($_SESSION['formData']['description'] ?? ''); ?></textarea>
        <br>
        <button type="submit" value="submit">Dodaj zdarzenie</button>
    </form>
</section>
</body>
</html>
