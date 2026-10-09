// Remove the Vue import entirely
// import { reactive, watchEffect } from 'vue'

// ─── Store — plain JS, no Vue reactivity ─────────────────────
export const flashState = { items: [] }
let uid = 0

export const dismissFlash = (id) => {
  const i = flashState.items.findIndex(x => x.id === id)
  if (i !== -1) {
    flashState.items.splice(i, 1)
    render()                      // ← draw immediately
    console.log('[useFlash] dismissed', id, '| remaining:', flashState.items.length)
  }
}

export const dismissAll = () => {
  flashState.items.length = 0
  render()
}

const push = (type, message, timeout = 4000) => {
  if (!message) return null
  const id = ++uid
  flashState.items.push({ id, type, message: String(message) })
  render()                        // ← draw immediately
  console.log('[useFlash] pushed', { id, type, message })
  if (timeout > 0) setTimeout(() => dismissFlash(id), timeout)
  return id
}

// ─── Styles ──────────────────────────────────────────────────
const STYLES = {
  success: {
    bg:     'linear-gradient(135deg,#059669 0%,#047857 100%)',
    accent: '#a7f3d0',
    glow:   'rgba(5,150,105,0.35)',
    icon:   'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
  },
  error: {
    bg:     'linear-gradient(135deg,#dc2626 0%,#b91c1c 100%)',
    accent: '#fecaca',
    glow:   'rgba(220,38,38,0.35)',
    icon:   'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
  },
  info: {
    bg:     'linear-gradient(135deg,#0284c7 0%,#0369a1 100%)',
    accent: '#bae6fd',
    glow:   'rgba(2,132,199,0.35)',
    icon:   'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
  },
}

// ─── DOM host ────────────────────────────────────────────────
let hostEl    = null
let hostReady = false

const buildToast = (item) => {
  const s = STYLES[item.type] || STYLES.info

  const el = document.createElement('div')
  el.dataset.id = String(item.id)
  el.setAttribute('role', 'status')
  el.style.cssText = `
    pointer-events:auto;
    border-radius:14px;
    border:none;
    background:${s.bg};
    color:#fff;
    box-shadow:0 20px 30px -10px ${s.glow}, 0 8px 12px -8px rgba(0,0,0,0.25);
    padding:14px 16px;
    display:flex;
    align-items:flex-start;
    gap:12px;
    font-family:Inter,system-ui,-apple-system,sans-serif;
    transform-origin:top right;
    animation:flashPop 260ms cubic-bezier(0.34,1.56,0.64,1);
    position:relative;
  `

  const iconWrap = document.createElement('div')
  iconWrap.style.cssText = `
    flex-shrink:0;width:26px;height:26px;
    display:flex;align-items:center;justify-content:center;
    border-radius:50%;
    background:rgba(255,255,255,0.18);
    color:${s.accent};
    margin-top:1px;
  `
  iconWrap.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="${s.icon}"/></svg>`

  const text = document.createElement('div')
  text.style.cssText = 'flex:1;font-size:13px;font-weight:500;line-height:1.4;color:#fff;word-break:break-word;letter-spacing:0.005em'
  text.textContent = item.message

  const close = document.createElement('button')
  close.type = 'button'
  close.setAttribute('aria-label', 'Dismiss')
  close.style.cssText = `
    flex-shrink:0;
    background:rgba(255,255,255,0.15);
    border:none;cursor:pointer;
    color:rgba(255,255,255,0.9);
    padding:4px;margin:-2px -2px 0 0;
    display:flex;align-items:center;justify-content:center;
    border-radius:6px;
    transition:background 120ms, color 120ms;
  `
  close.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px"><path d="M6 18L18 6M6 6l12 12"/></svg>'
  close.addEventListener('mouseenter', () => { close.style.background = 'rgba(255,255,255,0.28)'; close.style.color = '#fff' })
  close.addEventListener('mouseleave', () => { close.style.background = 'rgba(255,255,255,0.15)'; close.style.color = 'rgba(255,255,255,0.9)' })
  close.addEventListener('click', (e) => {
    e.preventDefault()
    e.stopPropagation()
    console.log('[useFlash] close clicked for id', item.id)
    dismissFlash(item.id)
  })

  el.appendChild(iconWrap)
  el.appendChild(text)
  el.appendChild(close)
  return el
}

const render = () => {
  if (!hostEl) return

  const wantedIds = new Set(flashState.items.map(i => String(i.id)))

  // Remove DOM nodes no longer in state
  Array.from(hostEl.children).forEach((node) => {
    if (!wantedIds.has(node.dataset.id)) {
      node.remove()
    }
  })

  // Append any new toasts (in order)
  flashState.items.forEach((item) => {
    const existing = hostEl.querySelector(`[data-id="${item.id}"]`)
    if (!existing) {
      hostEl.appendChild(buildToast(item))
    }
  })
}

const ensureHostMounted = () => {
  if (hostReady) return
  if (typeof document === 'undefined') return

  if (!document.getElementById('flash-toast-keyframes')) {
    const style = document.createElement('style')
    style.id = 'flash-toast-keyframes'
    style.textContent = `
      @keyframes flashPop {
        0%   { opacity: 0; transform: translateX(24px) scale(0.92); }
        60%  { opacity: 1; transform: translateX(-2px) scale(1.02); }
        100% { opacity: 1; transform: translateX(0)   scale(1); }
      }
    `
    document.head.appendChild(style)
  }

  document.getElementById('flash-host-root')?.remove()

  hostEl = document.createElement('div')
  hostEl.id = 'flash-host-root'
  hostEl.style.cssText = [
    'position:fixed',
    'top:20px',
    'right:20px',
    'z-index:2147483647',
    'display:flex',
    'flex-direction:column',
    'gap:10px',
    'width:min(92vw,24rem)',
    'pointer-events:none',
  ].join(';')
  document.body.appendChild(hostEl)

  hostReady = true
  console.log('[useFlash] host mounted ✓')
}

// ─── Public API ──────────────────────────────────────────────
export const useFlash = () => {
  ensureHostMounted()
  return {
    success: (msg, t = 4000) => push('success', msg, t),
    error:   (msg, t = 6500) => push('error',   msg, t),
    info:    (msg, t = 4000) => push('info',    msg, t),
    dismiss: dismissFlash,
    clear:   dismissAll,
  }
}

// ─── Debug helpers ───────────────────────────────────────────
if (typeof window !== 'undefined') {
  window.flashTest = (msg = 'Test toast') => {
    console.log('[useFlash] host exists:', !!document.getElementById('flash-host-root'))
    console.log('[useFlash] current items:', flashState.items)
    push('success', msg + ' at ' + new Date().toLocaleTimeString())
  }
  window.flashClear = () => dismissAll()
}