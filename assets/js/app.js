// Minimal JS (validation + small helpers)
function q(sel, ctx) { return (ctx || document).querySelector(sel); }
function qa(sel, ctx) { return [...(ctx || document).querySelectorAll(sel)]; }

// Simple confirm links
addEventListener('click', e => {
  const a = e.target.closest('a[data-confirm]');
  if (a && !confirm(a.getAttribute('data-confirm'))) { e.preventDefault(); }
});

// Basic front-end required validation fallback
addEventListener('submit', e => {
  const f = e.target;
  if (f.matches('[data-validate]')) {
    let ok = true;
    qa('[required]', f).forEach(inp => { if (!inp.value.trim()) { ok = false; inp.classList.add('err'); } });
    if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
  }
});
