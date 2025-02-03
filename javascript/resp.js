burger = document.querySelector(".burger");
navbar = document.querySelector(".navbar");
navLinks = document.querySelector(".nav-links");
rightNav = document.querySelector(".right-nav");

burger.addEventListener("click", () => {
  rightNav.classList.toggle("v-class-resp");
  navLinks.classList.toggle("v-class-resp");
  navbar.classList.toggle("h-nav-resp");
});
