<?php

class Comment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getByPost(int $postId): array {
        $sql = "SELECT c.*, u.username, u.nickname, u.avatar, u.role AS user_role
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :post_id
                ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(int $postId, int $userId, string $content): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)"
        );
        return $stmt->execute([':post_id' => $postId, ':user_id' => $userId, ':content' => $content]);
    }

    public function update(int $id, string $content): bool {
        $stmt = $this->db->prepare("UPDATE comments SET content=:content WHERE id=:id");
        return $stmt->execute([':content' => $content, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id=:id");
        return $stmt->execute([':id' => $id]);
    }
}