const swiper = new Swiper(".swiper-container", {
  keyboard: {
    enabled: true,
    onlyInViewport: false,
  },
  // Optional parameters
  loop: true,

  // Navigation arrows
  navigation: {
    nextEl: ".slider-button--next",
    prevEl: ".slider-button--prev",
  },
});
