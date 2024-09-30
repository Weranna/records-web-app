<?php
declare(strict_types=1);

class User {
    private string $login;
    private string $hashedPassword;
    private string $email;
    private string $userType;
    private string $userLocation;

    public function __construct(string $login, string $hashedPassword, string $email, string $userType, string $userLocation) {
        $this->login = $login;
        $this->hashedPassword = $hashedPassword;
        $this->email = $email;
        $this->userType = $userType;
        $this->userLocation = $userLocation;
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getUserType(): string {
        return $this->userType;
    }

    public function getUserLocation(): string {
        return $this->userLocation;
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->hashedPassword);
    }

    public function __toString(): string {
        return sprintf("User [Login: %s, Email: %s, UserType: %s, Location: %s]", 
            $this->login, $this->email, $this->userType, $this->userLocation);
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function validateUserData(array $data): array {
        $errors = [];
        
        if (empty($data['login'])) {
            $errors[] = 'Login jest wymagany.';
        }

        if (empty($data['pwd'])) {
            $errors[] = 'Hasło jest wymagane.';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Podaj poprawny adres e-mail.';
        }

        return $errors;
    }

    public static function validateUserDataOnEdit(array $data): array {
        $errors = [];
        
        if (empty($data['login'])) {
            $errors[] = 'Login jest wymagany.';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Podaj poprawny adres e-mail.';
        }

        return $errors;
    }

    public function addToDatabase(PDO $pdo): void {
        $query = "INSERT INTO users (login, pwd, email, user_group, location) VALUES (:login, :pwd, :email, :user_group, :location)";
        $stmt = $pdo->prepare($query);
        
        $stmt->execute([
            'login' => $this->login,
            'pwd' => $this->hashedPassword,
            'email' => $this->email,
            'user_group' => $this->userType,
            'location' => $this->userLocation
        ]);

        echo json_encode(['status' => 'success', 'table' => 'users']);
        $_SESSION['success'] = true;
        exit();
    }

    public function editUserInDatabase(PDO $pdo, int $id): void {
        // Przygotowanie zapytania do aktualizacji danych użytkownika
        $query = "UPDATE users SET login = :login, email = :email, user_group = :user_group, location = :location WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'login' => $this->login,
            'email' => $this->email,
            'user_group' => $this->userType,
            'location' => $this->userLocation,
            'id' => $id
        ]);

        echo json_encode(['status' => 'success', 'table' => 'users']);
        $_SESSION['success'] = true;
    }
}
