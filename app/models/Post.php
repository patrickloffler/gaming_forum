<?php

class Post {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(?int $categoryId = null): array {
        $sql = "SELECT p.*, u.nickname, u.username, u.avatar, c.name AS category_name, c.color AS category_color,
                       (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
                FROM posts p
                LEFT JOIN users u ON p.created_by = u.id
                LEFT JOIN categories c ON p.category_id = c.id";
        if ($categoryId) {
            $sql .= " WHERE p.category_id = :cat";
        }
        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        if ($categoryId) {
            $stmt->execute([':cat' => $categoryId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $sql = "SELECT p.*, u.nickname, u.username, u.avatar, u.role AS author_role,
                       c.name AS category_name, c.color AS category_color
                FROM posts p
                LEFT JOIN users u ON p.created_by = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUser(int $userId): array {
        $sql = "SELECT p.*, c.name AS category_name, c.color AS category_color,
                       (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.created_by = :uid
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(
        string $title, string $content, string $gameName,
        string $platform, int $categoryId, array $images, int $userId
    ): bool {
        $sql = "INSERT INTO posts (title, content, game_name, platform, category_id, images, created_by)
                VALUES (:title, :content, :game_name, :platform, :category_id, :images, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title'       => $title,
            ':content'     => $content,
            ':game_name'   => $gameName,
            ':platform'    => $platform,
            ':category_id' => $categoryId ?: null,
            ':images'      => json_encode($images),
            ':created_by'  => $userId,
        ]);
    }

    public function update(
        int $id, string $title, string $content, string $gameName,
        string $platform, int $categoryId, array $images
    ): bool {
        $sql = "UPDATE posts SET title=:title, content=:content, game_name=:game_name,
                platform=:platform, category_id=:category_id, images=:images
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $title,
            ':content'     => $content,
            ':game_name'   => $gameName,
            ':platform'    => $platform,
            ':category_id' => $categoryId ?: null,
            ':images'      => json_encode($images),
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function getLastInsertId(): int {
        return (int)$this->db->lastInsertId();
    }
}