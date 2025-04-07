import gsap from 'gsap';

export function fadeModalIn(modalId) {
    const modalElement = document.getElementById(modalId);
    const backdropElement = modalElement.querySelector('.modal-backdrop');

    // Fade-in animation for modal and backdrop
    gsap.fromTo(modalElement, { opacity: 0 }, { opacity: 1, duration: 0.5 });
    gsap.fromTo(backdropElement, { opacity: 0 }, { opacity: 0.5, duration: 0.5 });
}

export function fadeModalOut(modalId) {
    const modalElement = document.getElementById(modalId);
    const backdropElement = modalElement.querySelector('.modal-backdrop');

    // Fade-out animation for modal and backdrop
    gsap.fromTo(modalElement, { opacity: 1 }, { opacity: 0, duration: 0.5 });
    gsap.fromTo(backdropElement, { opacity: 0.5 }, { opacity: 0, duration: 0.5 });
}
