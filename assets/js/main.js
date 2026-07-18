// OWL Musical Instruments - shared front-end scripts

document.addEventListener('DOMContentLoaded', function () {
  // Mobile nav toggle
  var toggle = document.getElementById('navToggle');
  var links = document.getElementById('navLinks');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      links.classList.toggle('open');
    });
  }

  // Live "passwords match" check on the registration form, if present
  var pass = document.getElementById('password');
  var confirm = document.getElementById('confirm_password');
  var matchMsg = document.getElementById('passwordMatchMsg');

  function checkMatch() {
    if (!pass || !confirm || !matchMsg) return;
    if (confirm.value === '') {
      matchMsg.textContent = '';
      return;
    }
    if (pass.value === confirm.value) {
      matchMsg.textContent = 'Passwords match.';
      matchMsg.style.color = 'var(--success)';
    } else {
      matchMsg.textContent = 'Passwords do not match.';
      matchMsg.style.color = 'var(--danger)';
    }
  }

  if (pass && confirm) {
    pass.addEventListener('input', checkMatch);
    confirm.addEventListener('input', checkMatch);
  }
});
