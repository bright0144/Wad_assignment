document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.getElementById("hamburger");
  const nav = document.getElementById("mobileNav");

  if (!hamburger || !nav) return;

  hamburger.addEventListener("click", () => {
    nav.classList.toggle("max-h-0");
    nav.classList.toggle("max-h-[500px]");
    nav.classList.toggle("opacity-0");
    nav.classList.toggle("opacity-100");
  });
});
