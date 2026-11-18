(function () {
  const grid = document.getElementById('tvGrid');
  const sub = document.getElementById('tvSub');

  async function load() {
    try {
      const res = await fetch('/api/tv', { headers: { Accept: 'application/json' } });
      const data = await res.json();
      sub.textContent = `Nomor yang sedang dipanggil hari ini (${data.date}). Diperbarui otomatis.`;
      grid.innerHTML = data.items.map((i) => {
        const live = !!i.current;
        const label = !live ? 'Belum ada panggilan' : (i.current_status === 'examining' ? 'Sedang diperiksa' : 'Sedang dipanggil');
        return `
          <div class="tv-card ${live ? 'live' : ''} ${i.practicing ? '' : 'off'}">
            <div><div class="tv-poli">${esc(i.poli)}</div><div class="tv-doc">${esc(i.doctor)}</div></div>
            <div class="tv-now"><small>${label}</small><div class="num ${live ? '' : 'empty'}">${live ? esc(i.current) : '--'}</div></div>
            <div class="tv-foot"><span>Berikutnya: <b>${esc(i.next || '-')}</b></span><span>Menunggu: <b>${i.waiting}</b></span></div>
            ${i.practicing ? '' : '<div class="tv-off">Tidak praktik hari ini</div>'}
          </div>`;
      }).join('');
    } catch (_) { /* coba lagi pada interval berikutnya */ }
  }
  load();
  setInterval(load, 5000);
})();
