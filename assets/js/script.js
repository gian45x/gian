// SMOOTH SCROLLING BETWEEN SECTIONS
document.querySelectorAll('.scroll-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetSection = document.querySelector(targetId);
        if (targetSection) {
            window.scrollTo({
                top: targetSection.offsetTop - 60, // adjust for header height
                behavior: 'smooth'
            });
        }
    });
});

// SCROLL NI
const aboutSection = document.querySelector('#about');
window.addEventListener('scroll', () => {
    const revealPoint = window.innerHeight * 0.8;
    const sectionTop = aboutSection.getBoundingClientRect().top;
    if (sectionTop < revealPoint) {
        aboutSection.classList.add('show');
    } else {
        aboutSection.classList.remove('show');
    }
});

// FOOTER NI
function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById('datetime').textContent = `${date} ${time}`;
    document.getElementById('year').textContent = now.getFullYear();
}
setInterval(updateDateTime, 1000);
updateDateTime();
