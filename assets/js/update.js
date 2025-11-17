
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal and its elements
        const modal = document.getElementById('editImageModal');
        const closeBtn = document.querySelector('#editImageModal .close-btn');
        const editButtons = document.querySelectorAll('.btn-edit');

        // Get form inputs
        const editImageIdInput = document.getElementById('edit-image-id');
        const editCaptionPlaceholder = document.getElementById('edit-caption-placeholder');
        
        // --- 1. Close Modal Handlers ---

        // Close on X button click
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
        // Close on outside click
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        // --- 2. Open Modal and Data Transfer Handler ---
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data from the button's data attributes
                const imageId = this.getAttribute('data-id');
                const caption = this.getAttribute('data-caption'); // Still good to pass the existing caption
                
                // Populate the hidden fields in the form
                editImageIdInput.value = imageId;
                editCaptionPlaceholder.value = caption;
                
                // Reset file input (optional, but good practice)
                document.getElementById('edit-image-file').value = '';

                // Show the modal
                modal.style.display = "block";
            });
        });
    });