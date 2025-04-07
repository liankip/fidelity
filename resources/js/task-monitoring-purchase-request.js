import gsap from "gsap";

export function toggleCollapse(tab, prNoSanitized) {
    let content = document.getElementById(`collapse-${tab}-${prNoSanitized}`);
    let icon = document.getElementById(`icon-${tab}-${prNoSanitized}`);
    console.log(icon);

    if (!content) return;

    if (content.classList.contains("show")) {
        gsap.to(content, {
            height: 0,
            opacity: 0,
            duration: 0.5,
            ease: "power2.out",
            onComplete: () => {
                content.classList.remove("show");
                content.style.display = "none";
            }
        });
        gsap.to(icon, { rotate: 0, duration: 0.3 });
    } else {
        content.style.display = "block";
        let fullHeight = content.scrollHeight + "px";

        gsap.fromTo(content, { height: 0, opacity: 0 }, {
            height: fullHeight,
            opacity: 1,
            duration: 0.5,
            ease: "power2.out",
            onComplete: () => {
                content.classList.add("show");
                content.style.height = "auto";
            }
        });
        gsap.to(icon, { rotate: 180, duration: 0.3 });
    }
}
