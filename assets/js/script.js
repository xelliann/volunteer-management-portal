// Simple client-side validation helpers
document.addEventListener('DOMContentLoaded', () => {
  const reg = document.getElementById('registerForm');
  if (reg) {
    reg.addEventListener('submit', (e) => {
      const phone = reg.querySelector('input[name="phone"]').value.trim();
      if (phone !== '' && !/^\+?\d{7,15}$/.test(phone)) {
        e.preventDefault();
        alert('Phone must be 7-15 digits (optional +).');
      }
      const pwd = reg.querySelector('input[name="password"]').value;
      const conf = reg.querySelector('input[name="confirm_password"]').value;
      if (pwd.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters.');
      } else if (pwd !== conf) {
        e.preventDefault();
        alert('Passwords do not match.');
      }
    });
  }
});
