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
    <p class="title">ZGŁOŚ AWARIĘ</p>
    <button class="backButtonForm" onclick="window.location.href='homepage.php';">Wróć</button>
    <form class="mainForm"action="../includes/addreporth.inc.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <label for="beginDate">Data rozpoczęcia</label>
        <input type="date" id="beginDate" name="beginDate" value="<?php echo htmlspecialchars($_SESSION['formData']['beginDate'] ?? ''); ?>">
        <br>
        <label for="endDate">Data zakończenia</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($_SESSION['formData']['endDate'] ?? ''); ?>">
        <br>
        <label for="file">Załącznik</label>
        <input type="file" id="file" name="file">
        <br>
        <label for="description">Opis</label>
        <textarea name="description" id="description" placeholder="Opis"><?php echo htmlspecialchars($_SESSION['formData']['description'] ?? ''); ?></textarea>
        <br>
        <button type="submit" value="submit">Zgłoś awarię</button>
    </form>
</section>
</body>
</html>
