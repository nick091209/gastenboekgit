function toggleMenu() {
    const menu = document.querySelector(".menu-links");
    const icon = document.querySelector(".hamburger-icon");
    menu.classList.toggle("open");
    icon.classList.toggle("open");
  }

  // Open the login modal
function openLoginModal() {
  document.getElementById('loginModal').style.display = 'block';
}

// Close the login modal
function closeLoginModal() {
  document.getElementById('loginModal').style.display = 'none';
}
