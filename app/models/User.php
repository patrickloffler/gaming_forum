<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function register(
        string $username,
        string $email,
        string $password,
        ?string $firstName = null,
        ?string $lastName  = null,
        ?string $nickname  = null
    ): bool {
        if ($this->findByEmail($email) || $this->findByUsername($username)) {
            return false;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO users (username, email, password, first_name, last_name, nickname)
                 VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username'   => $username,
            ':email'      => $email,
            ':password'   => $hash,
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':nickname'   => $nickname,
        ]);
    }

    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id) {
        $stmt = $this->db->prepare(
            "SELECT id, username, email, first_name, last_name, nickname, avatar, bio, role, created_at
             FROM users WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT id, username, email, nickname, role, created_at FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $id, string $username, string $email, ?string $firstName, ?string $lastName, ?string $nickname, ?string $bio, ?string $avatar): bool {
        $sql = "UPDATE users SET username=:username, email=:email, first_name=:first_name,
                last_name=:last_name, nickname=:nickname, bio=:bio, avatar=:avatar
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'         => $id,
            ':username'   => $username,
            ':email'      => $email,
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':nickname'   => $nickname,
            ':bio'        => $bio,
            ':avatar'     => $avatar,
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password=:password WHERE id=:id");
        return $stmt->execute([':password' => $hash, ':id' => $id]);
    }

    public function updateRole(int $id, string $role): bool {
        $stmt = $this->db->prepare("UPDATE users SET role=:role WHERE id=:id");
        return $stmt->execute([':role' => $role, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=:id");
        return $stmt->execute([':id' => $id]);
    }
}