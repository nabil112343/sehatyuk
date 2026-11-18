// Filter artikel per poli di landing (tanpa reload)
(function () {
  const chips = document.querySelectorAll('[data-filter]');
  const cards = document.querySelectorAll('.art-grid [data-poli]');
  const empty = document.getElementById('artEmpty');
  chips.forEach((chip) => chip.addEventListener('click', () => {
    let shown = 0;
    chips.forEach((c) => c.classList.toggle('on', c === chip));
    cards.forEach((card) => {
      const ok = chip.dataset.filter === 'all' || card.dataset.poli === chip.dataset.filter;
      card.hidden = !ok;
      if (ok) shown++;
    });
    if (empty) empty.hidden = shown > 0;
  }));
})();
