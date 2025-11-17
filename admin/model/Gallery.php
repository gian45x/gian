<?php
// File: /project_root/model/Gallery.php

class Gallery
{
    private $conn;
    private $table_name = "gallery_images";

    // Database connection details
    private $host = "db.fr-pari1.bengt.wasmernet.com";
    private $db_name = "website_db"; // <-- Database name is set here
    private $username = '6c7fe90f738280009a84f4cc916d';   // <-- IMPORTANT: CHANGE THIS
    private $password = '06916c7f-e90f-7536-8000-8689e2fee785';   // <-- IMPORTANT: CHANGE THIS
    private $port = 10272; // <-- IMPORTANT: CHANGE THIS

    public function __construct()
    {
        $this->conn = null;
        try {
            // Establish the PDO connection with port
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set character set
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
        $query = "SELECT id, image_path, caption FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
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

    // --- UPDATE Function (NEW) ---

    /**
     * UPDATE - Update an existing image record (caption and/or file path)
     * @param array $data Must contain 'id' and at least one of 'caption' or 'image_path'
     */
    public function update(array $data): bool
    {
        if (!isset($data['id'])) {
            return false;
        }
        
        $setClauses = [];
        $params = [':id' => $data['id']];

        // 1. Add caption if provided
        if (isset($data['caption'])) {
            $data['caption'] = htmlspecialchars(strip_tags($data['caption']));
            $setClauses[] = 'caption = :caption';
            $params[':caption'] = $data['caption'];
        }

        // 2. Add image_path if provided
        if (isset($data['image_path'])) {
            $data['image_path'] = htmlspecialchars(strip_tags($data['image_path']));
            $setClauses[] = 'image_path = :image_path';
            $params[':image_path'] = $data['image_path'];
        }

        // Check if there's anything to update
        if (empty($setClauses)) {
            return false; 
        }

        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        try {
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating gallery image: " . $e->getMessage());
            return false;
        }
    }

    // --- FILE Helper Function (NEW) ---

    /**
     * Deletes the old file from the server when an image is replaced during an update.
     * @param int $id The ID of the record whose old file path should be deleted.
     */
    public function deleteOldFile(int $id): bool
    {
        $image_data = $this->readOne($id);
        if ($image_data) {
            // Check if a new image path was passed to prevent deleting the *new* file
            // The controller handles updating the DB. This method ONLY retrieves the current path and deletes the file.
            $file_path_to_delete = __DIR__ . '/../../' . $image_data['image_path'];
            
            // Safety check: ensure the file exists and is in the expected 'uploads/' directory
            if (file_exists($file_path_to_delete) && strpos($image_data['image_path'], 'uploads/') === 0) {
                return unlink($file_path_to_delete);
            }
        }
        return false;
    }


    // --- DELETE Function (SLIGHTLY MODIFIED) ---

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