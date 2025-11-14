<?php
// File: admin/controller/GalleryController.php

// Define the assumed base directory structure for file inclusion
require_once __DIR__ . '/../model/Gallery.php';

$gallery = new Gallery();

// Route the action based on the GET parameter
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'create':
        create($gallery);
        break;
    case 'delete':
        delete($gallery);
        break;
    case 'read_all':
        readAll($gallery);
        break;
    default:
        // Default error response for unknown actions
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unknown or missing action']);
        exit;
}

// --- CRUD FUNCTIONS ---

function create($gallery)
{
    // Check for POST request and a valid file upload
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image']['tmp_name'])) {
        // Redirect back with an error or show an error message
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../../admin/index.php') . '?error=no_file');
        exit;
    }

    $image_path = '';
    
    // Define the upload directory (two levels up from controller)
    $targetDir = __DIR__ . '/../../uploads/';
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    // Generate a unique file name to prevent overwrites (e.g., timestamp_originalfilename)
    $fileName = time() . '_' . basename($_FILES['image']['name']); 
    $filePath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
        // Path stored in DB must be relative to the web root (e.g., 'uploads/image.jpg')
        $image_path = 'uploads/' . $fileName;
    } else {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../../admin/index.php') . '?error=upload_failed');
        exit;
    }

    $data = [
        'image_path' => $image_path,
        // Assume you have a hidden input field named 'caption' in your upload form
        'caption'    => $_POST['caption'] ?? null 
    ];

    $gallery->create($data);

    // Redirect back to the originating page
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ../../admin/index.php');
    }
    exit;
}

/**
 * DELETE Function: Deletes image record from DB and the file from the server.
 */
function delete($gallery)
{
    $id = $_POST['id'] ?? null;

    if (empty($id) || !is_numeric($id)) {
        die('Invalid or missing image ID');
    }

    try {
        $result = $gallery->delete((int)$id);

        if ($result) {
            // ✅ Redirect back to the gallery page after delete
            header('Location: ../../index.php');
            exit;
        } else {
            die('Database deletion failed');
        }
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}


/**
 * READ ALL Function: Retrieves all images for front-end display (used via AJAX).
 */
function readAll($gallery)
{
    header('Content-Type: application/json');
    $images = $gallery->readAll();
    
    // Format the path for client-side use (adjust the '..' based on where you call this controller)
    $formatted_images = array_map(function($img) {
        // Assuming the controller is called from a page that is two levels up from 'uploads/'
        $img['full_web_path'] = '../../' . $img['image_path']; 
        return $img;
    }, $images);
    
    echo json_encode(['data' => $formatted_images]);
    exit;
}
?>