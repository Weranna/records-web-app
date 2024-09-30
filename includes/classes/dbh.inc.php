<?php
declare(strict_types=1);

class Dbh {
    private string $dsn;
    private string $dbusername;
    private string $dbpassword;
    protected ?PDO $pdo = null;

    public function __construct(string $host = 'localhost', string $dbname = 'recordswebapp', string $username = 'root', string $password = '') {
        $this->dsn = "mysql:host=$host;dbname=$dbname";
        $this->dbusername = $username;
        $this->dbpassword = $password;
        $this->connect();
    }

    private function connect(): void {
        try {
            $this->pdo = new PDO($this->dsn, $this->dbusername, $this->dbpassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
    
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }

    public function getConnection(): ?PDO {
        return $this->pdo;
    }
}
