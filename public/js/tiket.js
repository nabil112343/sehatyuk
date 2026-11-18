(function () {
  const box = document.getElementById('tickets');
  const fmtDate = (s) => s;

  function steps(t) {
    // 0 = berhasil ambil, 1 = menunggu, 2 = diperiksa, 3 = selesai
    const idx = { waiting: 1, skipped: 1, called: 2, examining: 2, done: 3 }[t.status] ?? 1;
    const items = [
      ['Berhasil ambil antrean', 'Silakan datang sesuai jadwal kunjungan.'],
      ['Menunggu giliran', 'Pantau posisi antrean Anda secara real-time.'],
      [t.status === 'called' ? 'Dipanggil' : 'Sedang diperiksa', 'Silakan menuju ruang periksa dokter.'],
      ['Selesai', 'Terima kasih telah berkunjung.'],
    ];
    return `<ul class="steps">${items.map((it, i) => {
      const cls = i < idx || (i === 3 && idx === 3) ? 'done' : (i === idx ? 'active' : '');
      return `<li class="${cls}"><span class="dot">${cls === 'done' || cls === 'active' ? '✓' : ''}</span><strong>${it[0]}</strong><small>${it[1]}</small></li>`;
    }).join('')}</ul>`;
  }

  function card(t) {
    const warn = t.status === 'skipped'
      ? '<div class="t-warn">Nomor Anda terlewat karena belum hadir saat dipanggil. Segera lapor ke petugas agar dilayani setelah pasien yang sedang diperiksa.</div>'
      : t.status === 'called' ? '<div class="t-warn" style="background:var(--warn-l);color:var(--warn)">Nomor Anda sedang dipanggil! Silakan menuju ruang periksa.</div>' : '';
    return `
      <article class="ticket">
        <div class="ticket-head">SehatYuk Klinik</div>
        <div class="ticket-body">
          <div class="t-status"><span class="badge st-${t.status}">${esc(t.status_label)}</span></div>
          ${warn}
          <div class="t-doc"><strong>${esc(t.doctor)}</strong><span>${esc(t.specialty)}</span></div>
          <div class="t-num">
            <small>Nomor Antrean ${esc(t.poli)}</small>
            <div class="code">${esc(t.queue_code)}</div>
            <div class="book">Kode booking: <b>${esc(t.booking_code)}</b></div>
          </div>
          <div class="t-stats">
            <div><small>Sisa antrean</small><b class="big">${t.ahead}</b></div>
            <div><small>Estimasi dilayani</small><b>${t.status === 'waiting' ? esc(t.estimate) : '-'}</b></div>
            <div><small>Kunjungan</small><b>${esc(fmtDate(t.visit_date))}</b></div>
          </div>
          <div class="t-line"></div>
          <div class="t-meta"><div>Nomor Rujukan<b>${esc(t.referral_number)}</b></div><div>Pasien<b>${esc(t.patient)}</b></div></div>
          <div class="t-title">Status Antrean :</div>
          ${steps(t)}
        </div>
      </article>`;
  }

  async function load() {
    try {
      const list = await api('/api/tiket-saya');
      box.innerHTML = list.length ? list.map(card).join('')
        : '<p class="muted">Anda belum punya tiket. <a href="/ambil-tiket">Ambil nomor antrean</a>.</p>';
    } catch (e) { box.innerHTML = `<p class="muted">${esc(e.message)}</p>`; }
  }
  load();
  setInterval(load, 10000);
})();
