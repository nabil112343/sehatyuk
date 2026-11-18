(function () {
  const loginForm = document.getElementById('loginForm');
  const regForm = document.getElementById('registerForm');
  const sub = document.getElementById('authSub');

  function show(which) {
    const reg = which === 'register';
    loginForm.hidden = reg;
    regForm.hidden = !reg;
    sub.textContent = reg ? 'Buat akun pasien baru' : 'Masuk untuk mengambil tiket antrean';
    showAlert('authError', '');
  }
  document.getElementById('showRegister').addEventListener('click', (e) => { e.preventDefault(); show('register'); });
  document.getElementById('showLogin').addEventListener('click', (e) => { e.preventDefault(); show('login'); });

  async function submit(form, url) {
    const btn = form.querySelector('button[type=submit]');
    const body = Object.fromEntries(new FormData(form));
    btn.disabled = true;
    showAlert('authError', '');
    try {
      const r = await api(url, { method: 'POST', body });
      location.href = r.redirect; // admin -> area admin, pasien -> area pasien
    } catch (err) {
      showAlert('authError', err.message);
      btn.disabled = false;
    }
  }
  loginForm.addEventListener('submit', (e) => { e.preventDefault(); submit(loginForm, '/login'); });
  regForm.addEventListener('submit', (e) => { e.preventDefault(); submit(regForm, '/register'); });

  if (new URLSearchParams(location.search).get('error') === 'google') {
    showAlert('authError', 'Login dengan Google gagal. Silakan coba lagi.');
  }
})();
