function validatePasswords() {
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirm_password").value;

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    document.getElementById("password").value = "";
    document.getElementById("confirm_password").value = "";
    return false; // Prevent form submission if passwords don't match 
  } if (password.length < 8) {
    alert("Password is too Small...!");
    document.getElementById("password").value = "";
    document.getElementById("confirm_password").value = "";
    return false;
  }
  // Passwords match
  return true; // Allow form submission if passwords match
}

function toggleMenu() {
  const menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden'); // Toggle hidden class using Tailwind
}

