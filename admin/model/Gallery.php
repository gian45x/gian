<?php
// File: /project_root/model/Gallery.php

class Gallery
{
    private $conn;
    private $table_name = "gallery_images";

    // Database connection details
    private $host = "localhost";
    private $db_name = "website_db"; // <-- Database name is set here
    private $username = 'root';   // <-- IMPORTANT: CHANGE THIS
    private $password = '';   // <-- IMPORTANT: CHANGE THIS

    public function __construct()
    {
        $this->conn = null;
        try {
            // Establish the PDO connection
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Set character set for the connection
            $this->conn->exec("set names utf8mb4");
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("Database connection failed. Check credentials in Gallery.php: " . $exception->getMessage());
        }
    }

    // --- READ Functions ---

    /**
     * READ ALL - Get all gallery images
     */
    public function readAll(): array
    {
        $query = "SELECT id, image_path, caption FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * READ ONE - Get a single image by ID
     */
    public function readOne($id): ?array
    {
        $query = "SELECT image_path, caption FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // --- CREATE Function ---

    /**
     * CREATE - Insert a new image record
     * @param array $data Must contain 'image_path' and optionally 'caption'
     */
    public function create(array $data): bool
    {
        $query = "INSERT INTO " . $this->table_name . " (image_path, caption) VALUES (:image_path, :caption)";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $data['image_path'] = htmlspecialchars(strip_tags($data['image_path']));
        $data['caption'] = htmlspecialchars(strip_tags($data['caption'] ?? ''));

        // Bind values
        $stmt->bindParam(":image_path", $data['image_path']);
        $stmt->bindParam(":caption", $data['caption']);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating gallery image: " . $e->getMessage());
            return false;
        }
    }

    // --- DELETE Function ---

    /**
     * DELETE - Delete an image record by ID and the associated file.
     */
    public function delete($id): bool
    {
        // 1. Get image path to delete the file
        $image_data = $this->readOne($id);
        if ($image_data) {
            // Path relative to the model file: /../../uploads/image.jpg
            $file_path_to_delete = __DIR__ . '/../../' . $image_data['image_path'];
            
            // Safety check: ensure the file exists and isn't the default logo
            if (file_exists($file_path_to_delete) && strpos($image_data['image_path'], 'uploads/') === 0) {
                unlink($file_path_to_delete);
            }
        }
        
        // 2. Delete the record from the database
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting gallery image: " . $e->getMessage());
            return false;
        }
    }
}
?>