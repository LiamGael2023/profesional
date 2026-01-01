<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $avatar;
    public $created_at;
    public $last_login;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar usuario por username o email
    public function findByUsernameOrEmail($identifier) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE (username = :identifier1 OR email = :identifier2)
                  AND is_active = 1
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':identifier1', $identifier);
        $stmt->bindParam(':identifier2', $identifier);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar login
    public function login($identifier, $password) {
        $user = $this->findByUsernameOrEmail($identifier);

        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último login
            $this->updateLastLogin($user['id']);
            return $user;
        }

        return false;
    }

    // Actualizar último login
    private function updateLastLogin($userId) {
        $query = "UPDATE " . $this->table . "
                  SET last_login = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    // Obtener usuario por ID
    public function findById($id) {
        $query = "SELECT id, username, email, full_name, avatar, created_at, last_login
                  FROM " . $this->table . "
                  WHERE id = :id AND is_active = 1
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo usuario
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (username, email, password, full_name)
                  VALUES (:username, :email, :password, :full_name)";

        $stmt = $this->conn->prepare($query);

        // Hash de la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':full_name', $data['full_name']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Verificar si username existe
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Verificar si email existe
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
