document.getElementById('delete-btn').addEventListener('click', function() {
    const currentImageId = document.getElementById('gallery-container').dataset.currentId; 

    if (!currentImageId) {
        alert("No image is currently selected for deletion.");
        return;
    }

    if (confirm("Are you sure you want to delete this image?")) {
        fetch('admin/controller/GalleryController.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Send the ID in the request body
            body: `id=${currentImageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Image deleted successfully!");
                // 2. Reload the gallery or move to the next image
                // Logic to update your HTML gallery view here
            } else {
                alert("Deletion failed: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred during deletion.");
        });
    }
});