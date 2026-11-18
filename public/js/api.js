/* Helper bersama: fetch JSON + CSRF, toast, modal, logout */
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

async function api(url, { method = 'GET', body } = {}) {
  const res = await fetch(url, {
    method,
    credentials: 'same-origin',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: body ? JSON.stringify(body) : undefined,
  });
  let data = null;
  try { data = await res.json(); } catch (_) {}
  if (!res.ok) {
    if (res.status === 401 || res.status === 419) { location.href = '/'; }
    const err = new Error(firstError(data) || 'Terjadi kesalahan, coba lagi.');
    err.status = res.status;
    throw err;
  }
  return data;
}

function firstError(d) {
  if (!d) return '';
  if (d.errors) { const v = Object.values(d.errors)[0]; return Array.isArray(v) ? v[0] : v; }
  return d.message || '';
}

const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const pad = (n) => String(n).padStart(2, '0');
const isoDate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

let toastTimer;
function toast(msg, type = 'ok') {
  const el = document.getElementById('toast');
  if (!el) return;
  el.textContent = msg;
  el.className = 'toast show' + (type === 'err' ? ' err' : '');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => (el.className = 'toast'), 3200);
}

function showModal(id) { document.getElementById(id).hidden = false; }
function hideModal(id) { document.getElementById(id).hidden = true; }
document.addEventListener('click', (e) => {
  const m = e.target.closest('.modal');
  if (m && (e.target === m || e.target.closest('[data-close]'))) m.hidden = true;
});

function showAlert(id, msg) {
  const el = document.getElementById(id);
  if (!el) return;
  el.textContent = msg || '';
  el.hidden = !msg;
}

document.getElementById('logoutBtn')?.addEventListener('click', async () => {
  try { const r = await api('/logout', { method: 'POST' }); location.href = r.redirect || '/'; }
  catch (_) { location.href = '/'; }
});
