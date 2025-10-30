const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");
const deleteBtn = document.getElementById("delete-btn");
const galleryImage = document.getElementById("gallery-image");

let images = [];
let currentIndex = 0;

async function loadImages() {
  try {
    const res = await fetch("fetch_images.php");
    const data = await res.json();
    images = data;

    if (images.length === 0) {
      galleryImage.src = "../assets/img/logo.png";
    } else {
      currentIndex = 0;
      updateImage();
    }
  } catch (error) {
    console.error("Error loading images:", error);
  }
}

function updateImage() {
  if (images.length === 0) return;
  galleryImage.src = images[currentIndex].url;
}

nextBtn.onclick = () => {
  if (images.length > 0) {
    currentIndex = (currentIndex + 1) % images.length;
    updateImage();
  }
};

prevBtn.onclick = () => {
  if (images.length > 0) {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateImage();
  }
};

deleteBtn.onclick = async () => {
  if (images.length === 0) return;
  if (!confirm("Delete this image?")) return;

  const id = images[currentIndex].id;
  const formData = new URLSearchParams();
  formData.append("id", id);

  const res = await fetch("../admin/controller/GalleryController.php?action=delete", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: formData,
  });

  const data = await res.json();

  if (data.success) {
    images.splice(currentIndex, 1);
    currentIndex = Math.max(0, currentIndex - 1);
    updateImage();
  } else {
    alert(data.error || "Delete failed");
  }
};

loadImages();
