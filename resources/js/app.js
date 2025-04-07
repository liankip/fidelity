import './bootstrap';
import './mazer'
import tooltip from "bootstrap/js/src/tooltip";

import {toggleCollapse} from "./task-monitoring-purchase-request";
import gsap from "gsap";

window.toggleCollapse = toggleCollapse;

document.addEventListener('shown.bs.modal', function () {
    setTimeout(() => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            gsap.to(backdrop, {opacity: 0, duration: 0.3, onComplete: () => backdrop.remove()});
        }
    }, 100);
});

document.addEventListener('hide.bs.modal', function () {
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        gsap.to(backdrop, {opacity: 0, duration: 0.3, onComplete: () => backdrop.remove()});
    }
});

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new tooltip(tooltipTriggerEl))
