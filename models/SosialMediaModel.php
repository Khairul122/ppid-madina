<?php
class SosialMediaModel
{
    private $conn;
    private $table_name = "sosial_media";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data sosial media dengan pagination dan search
    public function getAllSosialMedia($search = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE site LIKE :search OR facebook_link LIKE :search OR instagram_link LIKE :search OR instagram_post LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan total count dengan search
    public function getTotalCount($search = '')
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE site LIKE :search OR facebook_link LIKE :search OR instagram_link LIKE :search OR instagram_post LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Method untuk mendapatkan sosial media berdasarkan ID
    public function getSosialMediaById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_sosial_media = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk membuat sosial media baru
    public function create($site, $facebook_link = null, $instagram_link = null, $instagram_post = null)
    {
        $query = "INSERT INTO " . $this->table_name . " (site, facebook_link, instagram_link, instagram_post, created_at) VALUES (:site, :facebook_link, :instagram_link, :instagram_post, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':facebook_link', $facebook_link);
        $stmt->bindParam(':instagram_link', $instagram_link);
        $stmt->bindParam(':instagram_post', $instagram_post);
        
        return $stmt->execute();
    }

    // Method untuk update sosial media
    public function update($id, $site, $facebook_link = null, $instagram_link = null, $instagram_post = null)
    {
        $query = "UPDATE " . $this->table_name . " SET site = :site, facebook_link = :facebook_link, instagram_link = :instagram_link, instagram_post = :instagram_post, updated_at = NOW() WHERE id_sosial_media = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':site', $site);
        $stmt->bindParam(':facebook_link', $facebook_link);
        $stmt->bindParam(':instagram_link', $instagram_link);
        $stmt->bindParam(':instagram_post', $instagram_post);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Method untuk menghapus sosial media
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_sosial_media = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    // Method untuk memeriksa apakah site sudah ada
    public function siteExists($site, $id = null)
    {
        $query = "SELECT id_sosial_media FROM " . $this->table_name . " WHERE site = :site";
        $params = [':site' => $site];
        
        if ($id !== null) {
            $query .= " AND id_sosial_media != :id";
            $params[':id'] = $id;
        }
        
        $query .= " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}