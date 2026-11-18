(function () {
  const rows = document.getElementById('rows');
  const form = document.getElementById('form');
  const fPoli = document.getElementById('fPoli');
  const dayBoxes = [...document.querySelectorAll('#fDays input')];
  let doctors = [], editId = null;

  async function load() {
    const [docs, polis] = await Promise.all([api('/api/dokter'), api('/api/poli')]);
    doctors = docs;
    fPoli.innerHTML = polis.map((p) => `<option value="${p.id}">${esc(p.name)}</option>`).join('');
    rows.innerHTML = docs.map((d) => `
      <tr>
        <td><b>${esc(d.name)}</b><span class="ahead">${esc(d.specialty)}</span></td>
        <td>${esc(d.poli_name)}</td>
        <td>${esc(d.days.join(', '))}</td>
        <td>${d.start_time} - ${d.end_time}</td>
        <td>${d.quota}/hari</td>
        <td><div class="actions">
          <button class="btn btn-sm btn-outline" data-edit="${d.id}">Ubah</button>
          <button class="btn btn-sm btn-danger" data-del="${d.id}">Hapus</button>
        </div></td>
      </tr>`).join('') || '<tr><td colspan="6" class="empty">Belum ada dokter.</td></tr>';
  }

  function open(d) {
    editId = d?.id ?? null;
    document.getElementById('mTitle').textContent = d ? 'Ubah dokter' : 'Tambah dokter';
    if (!fPoli.options.length) { toast('Tambahkan data poli terlebih dahulu.', 'err'); return; }
    fPoli.value = d?.poli_id ?? fPoli.options[0].value;
    document.getElementById('fName').value = d?.name ?? '';
    document.getElementById('fSpec').value = d?.specialty ?? '';
    document.getElementById('fStart').value = d?.start_time ?? '08:00';
    document.getElementById('fEnd').value = d?.end_time ?? '13:00';
    document.getElementById('fQuota').value = d?.quota ?? 15;
    dayBoxes.forEach((c) => (c.checked = !!d && d.days.includes(c.value)));
    showAlert('mError', '');
    showModal('modal');
  }

  document.getElementById('btnAdd').addEventListener('click', () => open(null));
  rows.addEventListener('click', async (e) => {
    const ed = e.target.closest('[data-edit]'), del = e.target.closest('[data-del]');
    if (ed) open(doctors.find((d) => d.id == ed.dataset.edit));
    if (del) {
      const d = doctors.find((x) => x.id == del.dataset.del);
      if (!confirm(`Hapus ${d.name}? Tiket pasien untuk dokter ini juga akan terhapus.`)) return;
      try { await api(`/admin/api/dokter/${d.id}`, { method: 'DELETE' }); toast('Dokter dihapus'); load(); }
      catch (err) { toast(err.message, 'err'); }
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const body = {
      poli_id: fPoli.value,
      name: document.getElementById('fName').value.trim(),
      specialty: document.getElementById('fSpec').value.trim(),
      days: dayBoxes.filter((c) => c.checked).map((c) => c.value),
      start_time: document.getElementById('fStart').value,
      end_time: document.getElementById('fEnd').value,
      quota: Number(document.getElementById('fQuota').value),
    };
    try {
      await api(editId ? `/admin/api/dokter/${editId}` : '/admin/api/dokter', { method: editId ? 'PUT' : 'POST', body });
      hideModal('modal');
      toast(editId ? 'Data dokter diperbarui' : 'Dokter ditambahkan');
      load();
    } catch (err) { showAlert('mError', err.message); }
  });

  load().catch((e) => toast(e.message, 'err'));
})();
