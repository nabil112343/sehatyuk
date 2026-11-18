(async function () {
  const box = document.getElementById('poliList');
  try {
    const polis = await api('/api/poli');
    if (!polis.length) { box.innerHTML = '<p class="muted">Belum ada poliklinik.</p>'; return; }
    box.innerHTML = polis.map((p) => `
      <article class="poli-card">
        <div class="poli-head">
          <div>
            <h2>${esc(p.name)} <span class="chip">${esc(p.code)}</span></h2>
            <p>${esc(p.description)}</p>
          </div>
          <a class="btn btn-outline" href="/ambil-tiket?poli=${p.id}">Pilih dokter</a>
        </div>
        ${p.doctors.map((d) => `
          <div class="doc-box">
            <strong>${esc(d.name)}</strong>
            <div class="muted">${esc(d.specialty)}</div>
            <div class="doc-sched">${esc(d.days.join(', '))}<br>${d.start_time} - ${d.end_time} WIB · kuota ${d.quota}/hari</div>
          </div>`).join('') || '<p class="muted">Belum ada dokter di poli ini.</p>'}
      </article>`).join('');
  } catch (e) {
    box.innerHTML = `<p class="muted">${esc(e.message)}</p>`;
  }
})();
