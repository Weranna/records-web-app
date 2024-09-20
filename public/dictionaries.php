
<?php require_once '../includes/views/dictionariesview.inc.php' ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records Equipment</title>
    <link rel="stylesheet" href="assets/css/diclist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                <div class="popupButtons">
                <button id="yesBtn">Tak</button>
                <button id="noBtn">Nie</button>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-container">
    <form method="post" class="navForm">
        <input type="submit" name="action" value="Sprzęty" class="navButton">
    </form>
    <form method="post" class="navForm">
        <input type="submit" name="action" value="Słowniki" class="navButton active">
        <input type="hidden" name="redirect" value="dictionaries.php">
    </form>
        <form action="../includes/logouth.inc.php" method="post"  class="navForm">
            <button class="navButton">Wyloguj się</button>
        </form>
</div>
<div class="button-container">
    <?php foreach ($tableMapping as $displayName => $tableName): ?>
    <form method="get" action="dictionaries.php" class="table-form" data-table="<?php echo htmlspecialchars($tableName); ?>">
        <input type="submit" value="<?php echo htmlspecialchars($displayName); ?>" class="table-button <?php echo ($table === $tableName) ? 'active' : ''; ?>">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const forms = document.querySelectorAll('.table-form');

        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                forms.forEach(f => {
                    const button = f.querySelector('.table-button');
                    button.classList.remove('active');
                });

                const button = this.querySelector('.table-button');
                button.classList.add('active');

                this.submit();
            });
        });
    });
</script>

</body>
</html>