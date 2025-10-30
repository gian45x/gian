const header = document.getElementById("mainHeader");
const toggleBtn = document.getElementById("headerToggle");

let lastScrollTop = 0;

window.addEventListener("scroll", () => {
  let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  // Hide header when scrolling down
  if (scrollTop > lastScrollTop && scrollTop > 100) {
    header.classList.add("hidden");
    toggleBtn.classList.remove("hide");
  } else if (scrollTop < lastScrollTop) {
    // Optional: show again on scroll up
    header.classList.remove("hidden");
    toggleBtn.classList.add("hide");
  }

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});

// --- Button toggle logic ---
toggleBtn.addEventListener("click", () => {
  header.classList.toggle("hidden");
  toggleBtn.classList.toggle("hide");
});
