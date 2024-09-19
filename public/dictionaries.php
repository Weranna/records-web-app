
<?php require_once '../includes/views/dictionariesview.inc.php' ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records Equipment</title>
    <link rel="stylesheet" href="assets/css/diclist.css">
</head>
<body>
<script src="assets/scripts/popupDictAdd.js"></script>
<script src="assets/scripts/popupDictEdit.js"></script>
<script src="assets/scripts/popupDictDelete.js"></script>
<section class="wrapper-main">
    <div id="popup" class="popup">
        <div class="popup-content">
        <span class="close-btn" id="closeBtn">&times;</span>
            <div id="popupFormContainer">
                <p id="popupMessage"></p>
            </div>
        </div>
    </div>
    <div id="popupDel" class="popup">
        <div class="popup-content">
        <span class="close-btn" id="closeBtnDel">&times;</span>
            <div id="popupFormContainer">
                <p id="popupMessageDel"></p>
                <button id="yesBtn">Tak</button>
                <button id="noBtn">Nie</button>
            </div>
        </div>
    </div>
    <div class="button-container">
        <form method="post" class="navButtonForm">
            <input type="submit" name="action" value="Strona główna" class="navButton">
            <input type="hidden" name="redirect" value="homepage.php">
        </form>
    </div>
    <div class="button-container">
        <?php foreach ($tableMapping as $displayName => $tableName): ?>
        <form method="get" class="navButtonForm" action="dictionaries.php">
            <input type="submit" value="<?php echo htmlspecialchars($displayName); ?>" class="navButton">
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($tableName); ?>">
        </form>
        <?php endforeach; ?>
    </div>
    <?php
    if ($table && array_key_exists($table, $columnHeaders)) {
        displayTable($pdo, $table, $columnHeaders);
    }
    ?>
</section>
</body>
</html>