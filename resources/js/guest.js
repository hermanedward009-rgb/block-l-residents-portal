/** Minimal bundle for sign-in / register pages (no push or notification polling). */

document.querySelectorAll('.guest-hero-photo').forEach((img) => {
    const reveal = () => img.classList.add('loaded');
    if (img.complete) {
        reveal();
    } else {
        img.addEventListener('load', reveal, { once: true });
    }
});