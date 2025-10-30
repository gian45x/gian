<?php
// Fetch gallery images directly from the database
require_once __DIR__ . '/../admin/model/Gallery.php';

$gallery = new Gallery();
$images = $gallery->readAll(); // Get all images
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gian Albert's Portfolio</title>

  <!-- Core Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../assets/css/about.css" />
  <link rel="stylesheet" href="../assets/css/gallery.css" />

  <!-- External Libraries -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="vid">
  <!-- Background Video -->
  <video autoplay muted loop playsinline>
    <source src="../assets/video/pinterest-video-39.mp4" type="video/mp4" />
  </video>

  <!-- Header -->
  <header id="mainHeader">
    <div class="logo">MY PORTFOLIO</div>
    <nav>
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#gallery">Gallery</a>
      <a href="#contact">Contact</a>
    </nav>
  </header>

  <!-- Floating toggle button -->
  <button id="headerToggle" title="Show Menu">
    <i class="bx bx-menu"></i>
  </button>

  <!-- Home Section -->
  <section class="home" id="home">
    <div class="home-content">
      <h1>Hello, I'm Gian Albert</h1>
      <h3>INTERN STUDENT</h3>
      <p>
        <b>
          Possesses a strong work ethic, excellent communication skills, and a positive attitude.
          Quick learner, adaptable to new environments and committed to providing excellent service.
          Ready to contribute to a positive team environment and learn from experienced professionals.
        </b>
      </p>

      <div class="btn-box">
        <a href="#about" class="scroll-link">Show more...</a>
      </div>
    </div>

    <!-- Profile Orbit -->
    <div class="social-orbit-wrapper">
      <div class="profile-pic">
        <img src="../assets/images/gianpic.jpg" alt="Profile Picture of Gian Albert" />
      </div>

      <div id="orbit-center" class="orbit-container">
        <a href="https://www.tiktok.com/@morethan1234?_t=ZS-90mTaNIUWuq&_r=1" target="_blank" id="tiktok-icon" class="icon-item">
          <i class="fab fa-tiktok"></i>
        </a>
        <a href="https://www.instagram.com/giiyaann1?igsh=M3M3Z3VxMTF0enNm" target="_blank" id="instagram-icon" class="icon-item">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.facebook.com/gianalbert.mendaro.7" target="_blank" id="facebook-icon" class="icon-item">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" id="twitter-icon" class="icon-item">
          <i class="fab fa-x-twitter"></i>
        </a>
        <a href="#" id="viber-icon" class="icon-item">
          <i class="fab fa-viber"></i>
        </a>
        <div class="center-indicator"></div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about">
    <h2>About Me</h2>
    <p>
      Hello! I'm <b>Gian Albert Mendaro</b>, a dedicated and enthusiastic intern student with a passion
      for technology, design, and creativity. I love building clean and engaging websites using
      <b>HTML, CSS, JavaScript</b>, and a bit of <b>Java</b>. I enjoy exploring new technologies, solving problems,
      and continuously improving my skills.
      <br /><br />
      Outside of coding, I love watching anime and reading manga — both inspire me to think creatively
      and stay motivated. My goal is to grow into a skilled web developer who can design user-friendly
      and visually appealing digital experiences.
    </p>

    <div class="skills">
      <span>HTML</span>
      <span>CSS</span>
      <span>JavaScript</span>
      <span>Java</span>
      <span>Computer Literate</span>
      <span>Anime & Manga Enthusiast</span>
    </div>
  </section>

  <!-- Gallery Section -->
  <section id="gallery" class="gallery-section">
  <h2 class="gallery-title">Gallery</h2>

  <div class="section-content">
    <div class="gallery-grid">
      <?php if (!empty($images)): ?>
        <?php foreach ($images as $row): ?>
          <div class="gallery-card">
            <div class="gallery-image-wrapper">
              <img src="<?php echo '../' . htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['caption']); ?>">
            </div>
            <div class="gallery-info">
              <p><?php echo htmlspecialchars($row['caption']); ?></p>
              <form action="../admin/controller/GalleryController.php?action=delete" method="POST" class="delete-form" onsubmit="return confirm('Delete this image?');">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn-delete">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="no-images">No images found in the gallery.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- UPLOAD FORM -->
  <div class="gallery-upload">
    <form id="upload-form" action="../admin/controller/GalleryController.php?action=create" method="POST" enctype="multipart/form-data">
      <label for="image-file">Select Image:</label>
      <input type="file" name="image" id="image-file" required>
      <input type="hidden" name="caption" value="New Gallery Image">
      <button class="btn-glow upload" type="submit">Upload Image</button>
    </form>
  </div>
</section>

  <!-- Footer -->


  <!-- Scripts -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/script.js"></script>
  <script src="../assets/js/gallery.js"></script>
  <script src="../assets/js/header.js"></script>
  <script src="../assets/js/delete.js"></script>

</body>
</html>
