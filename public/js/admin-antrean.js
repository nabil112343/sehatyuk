(function () {
  const rows = document.getElementById('rows');
  const fDate = document.getElementById('fDate');
  const fDoctor = document.getElementById('fDoctor');
  fDate.value = isoDate(new Date());
  let busy = false;

  const fmt = (s) => { const [y, m, d] = s.split('-'); return `${d}-${m}-${y}`; };

  async function loadDoctors() {
    const docs = await api('/api/dokter');
    docs.forEach((d) => fDoctor.insertAdjacentHTML('beforeend', `<option value="${d.id}">${esc(d.name)}</option>`));
  }

  function actions(t) {
    const b = (act, label, cls = 'btn-outline') => `<button class="btn btn-sm ${cls}" data-act="${act}" data-id="${t.id}">${label}</button>`;
    const wa = (hot) => `<button class="btn btn-sm btn-wa ${hot ? 'hot' : ''}" data-act="wa" data-id="${t.id}">${t.notified ? '✓ ' : ''}Kirim WA</button>`;
    switch (t.status) {
      case 'waiting': return b('call', 'Panggil', 'btn-primary') + wa(t.ahead <= 2) + b('skip', 'Lewati', 'btn-danger');
      case 'called': return b('start', 'Mulai periksa', 'btn-primary') + b('skip', 'Lewati (telat)', 'btn-danger');
      case 'examining': return b('finish', 'Selesai', 'btn-primary');
      case 'skipped': return b('call', 'Panggil kembali', 'btn-primary') + wa(true);
      default: return '<span class="muted">-</span>';
    }
  }

  function render(list) {
    const c = (s) => list.filter((t) => t.status === s).length;
    document.getElementById('stats').innerHTML = [
      ['Menunggu', c('waiting')], ['Dipanggil / diperiksa', c('called') + c('examining')],
      ['Terlewat', c('skipped')], ['Selesai', c('done')], ['Total tiket', list.length],
    ].map(([l, n]) => `<div class="stat"><small>${l}</small><b>${n}</b></div>`).join('');

    rows.innerHTML = list.map((t) => `
      <tr class="${t.status === 'called' ? 'row-called' : ''}">
        <td class="qcode">${esc(t.queue_code)}</td>
        <td>${esc(t.patient)}</td>
        <td>${esc(t.doctor)}<span class="ahead">${esc(t.poli)}</span></td>
        <td>${esc(fmt(t.visit_date))}</td>
        <td>${esc(t.phone)}</td>
        <td><span class="badge st-${t.status}">${esc(t.status_label)}</span>${t.status === 'waiting' ? `<span class="ahead">${t.ahead} antrean di depan</span>` : ''}</td>
        <td><div class="actions">${actions(t)}</div></td>
      </tr>`).join('') || '<tr><td colspan="7" class="empty">Belum ada antrean pada tanggal ini.</td></tr>';
  }

  let current = [];
  async function load() {
    if (busy) return;
    const qs = new URLSearchParams();
    if (fDate.value) qs.set('date', fDate.value);
    if (fDoctor.value) qs.set('doctor_id', fDoctor.value);
    try { current = await api('/admin/api/antrean?' + qs); render(current); } catch (e) { toast(e.message, 'err'); }
  }

  rows.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-act]');
    if (!btn) return;
    const t = current.find((x) => x.id == btn.dataset.id);
    const act = btn.dataset.act;

    if (act === 'wa') {
      window.open(t.wa_url, '_blank', 'noopener'); // buka WhatsApp dengan pesan terisi
      try { await api(`/admin/api/antrean/${t.id}/notify`, { method: 'POST' }); load(); } catch (_) {}
      return;
    }
    if (act === 'skip' && !confirm(`Lewati ${t.queue_code} (${t.patient})? Antrean dilanjutkan ke pasien berikutnya.`)) return;

    busy = true; btn.disabled = true;
    try {
      await api(`/admin/api/antrean/${t.id}/${act}`, { method: 'POST' });
      toast({ call: `${t.queue_code} dipanggil`, start: 'Pemeriksaan dimulai', finish: 'Pemeriksaan selesai', skip: `${t.queue_code} dilewati` }[act]);
    } catch (err) { toast(err.message, 'err'); }
    busy = false; load();
  });

  document.getElementById('btnNext').addEventListener('click', async () => {
    if (!fDoctor.value) { toast('Pilih dokter terlebih dahulu.', 'err'); return; }
    try {
      const t = await api('/admin/api/antrean/next', { method: 'POST', body: { doctor_id: fDoctor.value } });
      toast(`${t.queue_code} (${t.patient}) dipanggil`);
      load();
    } catch (err) { toast(err.message, 'err'); }
  });

  document.getElementById('btnAll').addEventListener('click', () => { fDate.value = ''; load(); });
  fDate.addEventListener('change', load);
  fDoctor.addEventListener('change', load);

  loadDoctors().then(load);
  setInterval(load, 8000);
})();
