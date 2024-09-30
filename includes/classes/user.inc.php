<?php
declare(strict_types=1);

class User {
    private int $id;
    private string $login;
    private string $hashedPassword;
    private string $email;
    private string $userType;
    private string $userLocation;

    public function __construct(int $id, string $login, string $hashedPassword, string $email, string $userType, string $userLocation) {
        $this->id = $id;
        $this->login = $login;
        $this->hashedPassword = $hashedPassword;
        $this->email = $email;
        $this->userType = $userType;
        $this->userLocation = $userLocation;
    }

    public function getId(): int {
        return $this->id;
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
        return sprintf("User [ID: %d, Login: %s, Email: %s, UserType: %s, Location: %s]", 
            $this->id, $this->login, $this->email, $this->userType, $this->userLocation);
    }
}
