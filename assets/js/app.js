/// Minimal JS (validation + small helpers)
function qa(sel, ctx) { return [...(ctx || document).querySelectorAll(sel)]; }

// Basic front-end validation fallback
addEventListener('submit', e => {
  const f = e.target;
  if (f.matches('[data-validate]')) {
    let ok = true;
    qa('[required]', f).forEach(inp => { if (!inp.value.trim()) { ok = false; inp.classList.add('err'); } });
    if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
  }
});
//edited by haritha