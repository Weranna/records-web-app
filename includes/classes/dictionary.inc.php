<?php
declare(strict_types=1);

abstract class Dictionary {
    protected array $values;

    abstract public function validate(array $data): array;
    abstract public function add(PDO $pdo): void;
    abstract public function edit(PDO $pdo, int $id): void;

    protected function handleDatabaseError(PDOException $e): void {
        if ($e->getCode() === '23000') {
            $errorInfo = $e->errorInfo[2];
            $existingValue = '';

            foreach ($this->values as $column => $value) {
                if (strpos($errorInfo, $column) !== false) {
                    $existingValue = $value;
                    break;
                }
            }

            $_SESSION['errors'][] = "Taka pozycja już istnieje: \"$existingValue\"";
        } else {
            $_SESSION['errors'][] = "Wystąpił błąd: " . $e->getMessage();
        }
        
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }

    public static function handleDatabaseErrorOnDelete(PDOException $e): void {
        if ($e->getCode() === '23000') {
            $_SESSION['errors'][] = "Element jest używany, nie można go usunąć";
            echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
            exit();
        }
    }
    
    public function handleDatabaseErrorOnEdit(PDOException $e, array $tableMapping, array $validationResult): void { 
        if ($e->getCode() === '23000') {
            $errorInfo = $e->errorInfo[2];
            $existingValue = '';
    
            if (strpos($errorInfo, 'FOREIGN KEY') !== false) {
                $_SESSION['errors'][] = 'Nie można zmienić tej wartości, ponieważ jest używana w innych tabelach.';
            } else {
                foreach ($tableMapping as $column) {
                    if (strpos($errorInfo, $column) !== false) {
                        $existingValue = $validationResult['inputValues'][$column] ?? '';
                        break;
                    }
                }
                $_SESSION['errors'][] = "Taka pozycja już istnieje: \"$existingValue\"";
            }
        } else {
            $_SESSION['errors'][] = "Wystąpił błąd: " . $e->getMessage();
        }
        echo json_encode(['status' => 'error', 'message' => $_SESSION['errors']]);
        exit();
    }

    public static function get(PDO $pdo, string $table, int $id): ?array {
        $query = "SELECT * FROM " . $table . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete(PDO $pdo, string $table, int $id): void {
        $query = "DELETE FROM " . $table . " WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        echo json_encode(['status' => 'success', 'table' => $table]);
        $_SESSION['success'] = true;
    }
}

class DeviceDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa urządzenia jest wymagana.';
        }
        $this->values = ['name' => htmlspecialchars($data['name'])];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO devices (name) VALUES (:name)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name']]);

        echo json_encode(['status' => 'success', 'table' => 'devices']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE devices SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name'], 'id' => $id]);
        echo json_encode(['status' => 'success', 'table' => 'devices']);
        $_SESSION['success'] = true;
    }

}

class ManufacturerDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa producenta jest wymagana.';
        }
        $this->values = ['name' => htmlspecialchars($data['name'])];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO manufacturers (name) VALUES (:name)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name']]);

        echo json_encode(['status' => 'success', 'table' => 'manufacturers']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE manufacturers SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name'], 'id' => $id]);
        echo json_encode(['status' => 'success', 'table' => 'manufacturers']);
        $_SESSION['success'] = true;
    }


    
}

class SupplierDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa dostawcy jest wymagana.';
        }
        if (empty($data['address'])) {
            $errors[] = 'Adres dostawcy jest wymagany.';
        }
        if (empty($data['phone'])) {
            $errors[] = 'Numer telefonu dostawcy jest wymagany.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Podaj poprawny email.';
        }
        $this->values = [
            'name' => htmlspecialchars($data['name']),
            'address' => htmlspecialchars($data['address']),
            'phone' => htmlspecialchars($data['phone']),
            'email' => htmlspecialchars($data['email']),
        ];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO suppliers (name, address, phone, email) VALUES (:name, :address, :phone, :email)";
        $stmt = $pdo->prepare($query);
        $stmt->execute($this->values);

        echo json_encode(['status' => 'success', 'table' => 'suppliers']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE suppliers SET name = :name, address = :address, phone = :phone, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $this->values['id'] = $id;
        $stmt->execute($this->values);
        echo json_encode(['status' => 'success', 'table' => 'suppliers']);
        $_SESSION['success'] = true;
    }

  

   
}

class LocationDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa lokalizacji jest wymagana.';
        }
        $this->values = ['name' => htmlspecialchars($data['name'])];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO locations (name) VALUES (:name)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name']]);

        echo json_encode(['status' => 'success', 'table' => 'locations']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE locations SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $this->values['id'] = $id;
        $stmt->execute($this->values);
        echo json_encode(['status' => 'success', 'table' => 'locations']);
        $_SESSION['success'] = true;
    }



  
}

class StatusDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa statusu jest wymagana.';
        }
        $this->values = ['name' => htmlspecialchars($data['name'])];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO statuses (name) VALUES (:name)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name']]);

        echo json_encode(['status' => 'success', 'table' => 'statuses']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE statuses SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $this->values['id'] = $id;
        $stmt->execute($this->values);
        echo json_encode(['status' => 'success', 'table' => 'statuses']);
        $_SESSION['success'] = true;
    }

  

  
}

class EventDictionary extends Dictionary {
    public function validate(array $data): array {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nazwa wydarzenia jest wymagana.';
        }
        $this->values = ['name' => htmlspecialchars($data['name'])];
        return ['errors' => $errors];
    }

    public function add(PDO $pdo): void {
        $query = "INSERT INTO events (name) VALUES (:name)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['name' => $this->values['name']]);

        echo json_encode(['status' => 'success', 'table' => 'events']);
        $_SESSION['success'] = true;
    }

    public function edit(PDO $pdo, int $id): void {
        $query = "UPDATE events SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $this->values['id'] = $id;
        $stmt->execute($this->values);
        echo json_encode(['status' => 'success', 'table' => 'events']);
        $_SESSION['success'] = true;
    }


    
}
