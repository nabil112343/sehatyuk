(async function () {
  const grid = document.getElementById('docGrid');
  const fSearch = document.getElementById('fSearch');
  const fPoli = document.getElementById('fPoli');
  const fDay = document.getElementById('fDay');
  let doctors = [];
  let selected = null;

  try {
    const [docs, polis] = await Promise.all([api('/api/dokter'), api('/api/poli')]);
    doctors = docs;
    polis.forEach((p) => fPoli.insertAdjacentHTML('beforeend', `<option value="${p.id}">${esc(p.name)}</option>`));
    const pre = new URLSearchParams(location.search).get('poli');
    if (pre) fPoli.value = pre;
  } catch (e) { grid.innerHTML = `<div class="empty-box">${esc(e.message)}</div>`; return; }

  function render() {
    const q = fSearch.value.trim().toLowerCase();
    const list = doctors.filter((d) =>
      (!q || (d.name + ' ' + d.specialty).toLowerCase().includes(q)) &&
      (!fPoli.value || String(d.poli_id) === fPoli.value) &&
      (!fDay.value || d.days.includes(fDay.value)));
    document.getElementById('count').textContent = `Menampilkan ${list.length} dokter`;
    grid.innerHTML = list.map((d) => `
      <div class="doc-card">
        <div class="doc-top">
          <div class="doc-av">${esc(d.name.replace(/^(dr\.|drg\.|Dr\.)\s*/i, '').charAt(0))}</div>
          <div><div class="doc-poli">${esc(d.poli_name)}</div><div class="doc-name">${esc(d.name)}</div><div class="doc-spec">${esc(d.specialty)}</div></div>
        </div>
        <div class="doc-info">
          <div><b>Hari:</b> ${esc(d.days.join(', '))}</div>
          <div><b>Jam:</b> ${d.start_time} - ${d.end_time} WIB</div>
          <div><b>Kuota:</b> ${d.quota} pasien/hari</div>
        </div>
        <button class="btn btn-primary btn-block" data-id="${d.id}">Ambil nomor antrean</button>
      </div>`).join('') || '<div class="empty-box">Tidak ada dokter yang cocok.</div>';
  }
  [fSearch, fPoli, fDay].forEach((el) => el.addEventListener('input', render));
  render();

  // ---- Modal ----
  const mDate = document.getElementById('mDate');
  const mHint = document.getElementById('mHint');

  function nextPracticeDate(d) {
    const t = new Date();
    for (let i = 0; i < 8; i++) {
      const past = i === 0 && `${pad(t.getHours())}:${pad(t.getMinutes())}` >= d.end_time;
      if (!past && d.days.includes(DAYS[t.getDay()])) return isoDate(t);
      t.setDate(t.getDate() + 1);
    }
    return isoDate(new Date());
  }

  function checkDate() {
    if (!selected || !mDate.value) return true;
    const day = DAYS[new Date(mDate.value + 'T00:00:00').getDay()];
    const ok = selected.days.includes(day);
    mHint.textContent = ok ? `Dokter praktik hari ${day}, pukul ${selected.start_time} - ${selected.end_time} WIB.`
                           : `Dokter tidak praktik hari ${day}. Jadwal: ${selected.days.join(', ')}.`;
    mHint.style.color = ok ? '' : 'var(--danger)';
    return ok;
  }
  mDate.addEventListener('input', checkDate);

  grid.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-id]');
    if (!btn) return;
    selected = doctors.find((d) => d.id == btn.dataset.id);
    document.getElementById('mDoctor').textContent = `${selected.name} · ${selected.poli_name}`;
    mDate.min = isoDate(new Date());
    mDate.value = nextPracticeDate(selected);
    showAlert('mError', '');
    checkDate();
    showModal('ticketModal');
  });

  document.getElementById('ticketForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!checkDate()) { showAlert('mError', 'Pilih tanggal sesuai jadwal praktik dokter.'); return; }
    const btn = e.target.querySelector('button[type=submit]');
    btn.disabled = true;
    try {
      await api('/api/tiket', { method: 'POST', body: {
        doctor_id: selected.id, visit_date: mDate.value, phone: document.getElementById('mPhone').value.trim(),
      }});
      toast('Tiket berhasil diambil!');
      setTimeout(() => (location.href = '/tiket-saya'), 600);
    } catch (err) {
      showAlert('mError', err.message);
      btn.disabled = false;
    }
  });
})();
