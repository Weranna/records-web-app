<?php 
declare(strict_types=1);
require_once '../includes/views/loginview.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Records Equipment</title>
</head>
<body>
    <form action="../includes/loginh.inc.php" method="post">
        <label class="title">Zaloguj się</label>
        <input type="text" id="login" name="login" placeholder="Nazwa użytkownika"  autocomplete="off" value="<?php echo htmlspecialchars($_SESSION['formData']['login'] ?? ''); ?>">
        <input type="password" name="password" placeholder="Hasło">
        <button>Potwierdź</button>
    </form>
</body>
</html>