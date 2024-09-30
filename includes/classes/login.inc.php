<?php
declare(strict_types=1);

class Login {
    public static function isInputEmpty(string $login, string $password): array {
        $errors = [];
        if (empty($login)) $errors[] = 'Login jest wymagany.';
        if (empty($password)) $errors[] = 'Hasło jest wymagane.';
        return $errors;
    }

    public static function isLoginInvalid($result): bool {
        return !$result;
    }

    public static function isPasswordInvalid(string $password, string $hashedPwd): bool {
        return !password_verify($password, $hashedPwd);
    }

    public static function getUser(PDO $pdo, string $login): ?array {
        $query = "SELECT * FROM users WHERE login = :login";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":login", $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
