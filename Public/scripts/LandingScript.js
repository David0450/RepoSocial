

// Animaciones de entrada con GSAP
gsap.from(".hero-text h2", {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.3,
});

gsap.from(".hero-text p", {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.6,
});

gsap.from(".hero-buttons", {
  y: 50,
  opacity: 0,
  duration: 1,
  delay: 0.9,
});

document.querySelectorAll(".feature-section").forEach((section, index) => {
    const text = section.querySelector(".text");
    const image = section.querySelector(".image");

    // Si es impar, invertimos las direcciones
    const isOdd = index % 2 !== 0;
    const textFrom = { x: isOdd ? 100 : -100, opacity: 0 };
    const imageFrom = { x: isOdd ? -100 : 100, opacity: 0 };

    gsap.fromTo(
        text,
        textFrom,
        {
            x: 0,
            opacity: 1,
            duration: 1,
            scrollTrigger: {
                trigger: section,
                start: "top center"
            }
        }
    );

    gsap.fromTo(
        image,
        imageFrom,
        {
            x: 0,
            opacity: 1,
            duration: 1,
            scrollTrigger: {
                trigger: section,
                start: "top center"
            }
        }
    );
});
