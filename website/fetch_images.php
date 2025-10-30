<?php
// File: /website/fetch_images.php

header('Content-Type: application/json');

// Fetch data from the controller (read_all)
$response = file_get_contents('../admin/controller/GalleryController.php?action=read_all');

if ($response === false) {
    echo json_encode([]);
    exit;
}

// Decode and reformat to match what gallery.js expects
$data = json_decode($response, true);
if (!isset($data['data'])) {
    echo json_encode([]);
    exit;
}

// Format it so gallery.js can use .url and .id
$images = array_map(function($img) {
    return [
        'id' => $img['id'],
        'url' => '../' . $img['image_path'], // relative path for frontend
        'caption' => $img['caption'] ?? ''
    ];
}, $data['data']);

echo json_encode($images);
?>
