(function () {
  const rows = document.getElementById('rows');
  const form = document.getElementById('form');
  let polis = [], editId = null;

  async function load() {
    polis = await api('/api/poli');
    rows.innerHTML = polis.map((p) => `
      <tr>
        <td><span class="badge st-examining">${esc(p.code)}</span></td>
        <td><b>${esc(p.name)}</b></td>
        <td>${esc(p.description || '-')}</td>
        <td>${p.doctors.length}</td>
        <td><div class="actions">
          <button class="btn btn-sm btn-outline" data-edit="${p.id}">Ubah</button>
          <button class="btn btn-sm btn-danger" data-del="${p.id}">Hapus</button>
        </div></td>
      </tr>`).join('') || '<tr><td colspan="5" class="empty">Belum ada poli.</td></tr>';
  }

  function open(p) {
    editId = p?.id ?? null;
    document.getElementById('mTitle').textContent = p ? 'Ubah poli' : 'Tambah poli';
    document.getElementById('fCode').value = p?.code ?? '';
    document.getElementById('fName').value = p?.name ?? '';
    document.getElementById('fDesc').value = p?.description ?? '';
    showAlert('mError', '');
    showModal('modal');
  }

  document.getElementById('btnAdd').addEventListener('click', () => open(null));
  rows.addEventListener('click', async (e) => {
    const ed = e.target.closest('[data-edit]'), del = e.target.closest('[data-del]');
    if (ed) open(polis.find((p) => p.id == ed.dataset.edit));
    if (del) {
      const p = polis.find((x) => x.id == del.dataset.del);
      if (!confirm(`Hapus ${p.name}? Dokter dan tiket di poli ini juga akan terhapus.`)) return;
      try { await api(`/admin/api/poli/${p.id}`, { method: 'DELETE' }); toast('Poli dihapus'); load(); }
      catch (err) { toast(err.message, 'err'); }
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const body = {
      code: document.getElementById('fCode').value.trim(),
      name: document.getElementById('fName').value.trim(),
      description: document.getElementById('fDesc').value.trim(),
    };
    try {
      await api(editId ? `/admin/api/poli/${editId}` : '/admin/api/poli', { method: editId ? 'PUT' : 'POST', body });
      hideModal('modal');
      toast(editId ? 'Poli diperbarui' : 'Poli ditambahkan');
      load();
    } catch (err) { showAlert('mError', err.message); }
  });

  load().catch((e) => toast(e.message, 'err'));
})();
