// Smooth scroll to About section
document.querySelectorAll('a[href="#about"], .scroll-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const aboutSection = document.querySelector('#about');
        aboutSection.classList.add('show');
        window.scrollTo({
            top: aboutSection.offsetTop - 50,
            behavior: 'smooth'
        });
    });
});

// Reveal About section when scrolling into view
window.addEventListener('scroll', () => {
    const aboutSection = document.querySelector('#about');
    const rect = aboutSection.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
        aboutSection.classList.add('show');
    }
});
