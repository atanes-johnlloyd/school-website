import { reactive, ref, watch, h, createApp, onMounted, onUnmounted } from 'vue'

// ─── State ───────────────────────────────────────────────────
export const confirmState = reactive({
  open: false,
  title: '',
  message: '',
  details: [],
  confirmLabel: 'Confirm',
  cancelLabel: 'Cancel',
  variant: 'info',
  requireReason: false,
  reasonLabel: 'Reason',
  reasonPlaceholder: '',
  reasonRequired: false,
  _resolve: null,
})

const close = (confirmed, reason = '') => {
  const resolve = confirmState._resolve
  confirmState._resolve = null
  confirmState.open = false
  if (resolve) resolve({ confirmed, reason })
}

export const confirmAccept = (reason = '') => close(true, reason)
export const confirmReject = () => close(false, '')

// ─── Variant styles ──────────────────────────────────────────
const VARIANTS = {
  info: {
    confirmClass: 'bg-[#004d08] hover:bg-emerald-900 text-white',
    iconColor:    'text-sky-600 dark:text-sky-400',
    iconBg:       'bg-sky-50 dark:bg-sky-950/40 border-sky-100 dark:border-sky-900/50',
    iconPath:     'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
  },
  warning: {
    confirmClass: 'bg-amber-500 hover:bg-amber-600 text-white',
    iconColor:    'text-amber-600 dark:text-amber-400',
    iconBg:       'bg-amber-50 dark:bg-amber-950/40 border-amber-100 dark:border-amber-900/50',
    iconPath:     'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
  },
  danger: {
    confirmClass: 'bg-red-600 hover:bg-red-700 text-white',
    iconColor:    'text-red-600 dark:text-red-400',
    iconBg:       'bg-red-50 dark:bg-red-950/40 border-red-100 dark:border-red-900/50',
    iconPath:     'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
  },
}

// ─── Host component ──────────────────────────────────────────
const ConfirmHost = {
  name: 'ConfirmHost',
  setup() {
    const reason = ref('')

    // sync flush so we don't race with the state change
    watch(
      () => confirmState.open,
      (open) => {
        const dlg = document.getElementById('confirm-host-root')
        if (!dlg) return

        if (open) {
          reason.value = ''
          if (!dlg.open) {
            try { dlg.showModal() } catch (e) { console.error('[useConfirm] showModal failed:', e) }
          }
        } else {
          if (dlg.open) dlg.close()
        }
      },
      { flush: 'sync' }
    )

    // Cmd/Ctrl+Enter to accept. Esc is handled natively by <dialog>.
    const onKey = (e) => {
      if (!confirmState.open) return
      if (e.key === 'Enter' && (e.metaKey || e.ctrlKey)) {
        const can = !confirmState.reasonRequired || reason.value.trim().length > 0
        if (can) confirmAccept(reason.value.trim())
      }
    }
    onMounted(()   => window.addEventListener('keydown', onKey))
    onUnmounted(() => window.removeEventListener('keydown', onKey))

    return () => {
      if (!confirmState.open) return null

      const v = VARIANTS[confirmState.variant] || VARIANTS.info
      const canConfirm = !confirmState.reasonRequired || reason.value.trim().length > 0

      return h('div', {
        // position:fixed inside a modal <dialog> is viewport-relative,
        // so this fills the screen and centers the card. The dark
        // backdrop is provided by ::backdrop (see injected style below).
        style: 'position:fixed;inset:0;display:flex;align-items:center;justify-content:center;padding:1rem;',
        onClick: (e) => { if (e.target === e.currentTarget) confirmReject() },
      }, [
        h('div', {
          class: "w-full max-w-lg bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] shadow-2xl font-['Inter']",
          style: 'animation:flashPopIn 220ms cubic-bezier(0.34,1.56,0.64,1)',
        }, [
          // Header
          h('div', { class: 'flex items-start gap-3 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]' }, [
            h('div', { class: `w-10 h-10 rounded-2xl border flex items-center justify-center shrink-0 ${v.iconBg} ${v.iconColor}` }, [
              h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'stroke-width': 2 }, [
                h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: v.iconPath }),
              ]),
            ]),
            h('div', { class: 'min-w-0 flex-1 pt-1' }, [
              h('h3', { class: 'text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white' },
                confirmState.title || 'Confirm'),
            ]),
          ]),

          // Body
          h('div', { class: 'px-6 py-5 space-y-3' }, [
            confirmState.message
              ? h('p', { class: 'text-xs text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line' },
                  confirmState.message)
              : null,

            confirmState.details.length
              ? h('ul', { class: 'space-y-2 pt-1' },
                  confirmState.details.map((d, i) =>
                    h('li', { key: i, class: 'flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400' }, [
                      h('span', { class: 'mt-1.5 w-1 h-1 rounded-full bg-gray-400 dark:bg-gray-500 shrink-0' }),
                      h('span', { class: 'leading-relaxed' }, d),
                    ])
                  )
                )
              : null,

            confirmState.requireReason
              ? h('div', { class: 'space-y-1.5 pt-1' }, [
                  h('label', { class: 'block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider' }, [
                    confirmState.reasonLabel,
                    confirmState.reasonRequired ? h('span', { class: 'text-red-500 ml-0.5' }, '*') : null,
                  ]),
                  h('textarea', {
                    value: reason.value,
                    rows: 3,
                    placeholder: confirmState.reasonPlaceholder || 'Enter reason…',
                    onInput: (e) => { reason.value = e.target.value },
                    class: 'w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none resize-none',
                  }),
                ])
              : null,
          ]),

          // Footer
          h('div', { class: 'px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2.5' }, [
            h('button', {
              type: 'button',
              onClick: () => confirmReject(),
              class: 'px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer',
            }, confirmState.cancelLabel),
            h('button', {
              type: 'button',
              disabled: !canConfirm,
              onClick: () => confirmAccept(reason.value.trim()),
              class: `px-5 py-2 text-xs font-medium uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer ${v.confirmClass}`,
            }, confirmState.confirmLabel),
          ]),
        ]),
      ])
    }
  },
}

// ─── Mount ───────────────────────────────────────────────────
let hostMounted = false

const ensureHostMounted = () => {
  if (hostMounted) return
  if (typeof document === 'undefined') return
  if (!document.body) { requestAnimationFrame(ensureHostMounted); return }

  // Clear any stale host left from an HMR cycle
  document.getElementById('confirm-host-root')?.remove()

  const dlg = document.createElement('dialog')
  dlg.id = 'confirm-host-root'
  dlg.style.cssText = [
    'position:fixed',
    'inset:0',
    'width:100vw',
    'height:100vh',
    'max-width:100vw',
    'max-height:100vh',
    'padding:0',
    'margin:0',
    'border:none',
    'background:transparent',
    'color:inherit',
    'overflow:visible',
  ].join(';')

  // Esc — let our state drive the close so the promise resolves properly.
  dlg.addEventListener('cancel', (e) => {
    e.preventDefault()
    if (confirmState.open) confirmReject()
  })

  document.body.appendChild(dlg)

  try {
    createApp(ConfirmHost).mount(dlg)
    hostMounted = true
    console.log('[useConfirm] mounted ✓ (dialog top-layer)')
  } catch (err) {
    console.error('[useConfirm] mount failed:', err)
  }
}

// ─── Public API ──────────────────────────────────────────────
export const useConfirm = () => {
  return (opts = {}) => new Promise((resolve) => {
    if (confirmState._resolve) {
      confirmState._resolve({ confirmed: false, reason: '' })
    }

    ensureHostMounted()

    confirmState.title             = opts.title || 'Are you sure?'
    confirmState.message           = opts.message || ''
    confirmState.details           = opts.details || []
    confirmState.confirmLabel      = opts.confirmLabel || 'Confirm'
    confirmState.cancelLabel       = opts.cancelLabel || 'Cancel'
    confirmState.variant           = opts.variant || 'info'
    confirmState.requireReason     = !!opts.requireReason
    confirmState.reasonLabel       = opts.reasonLabel || 'Reason'
    confirmState.reasonPlaceholder = opts.reasonPlaceholder || ''
    confirmState.reasonRequired    = opts.requireReason && opts.reasonRequired !== false
    confirmState._resolve          = resolve
    confirmState.open              = true      // flips the watcher → showModal()
  })
}

// ─── Injected styles + debug helpers ─────────────────────────
if (typeof document !== 'undefined') {
  if (!document.getElementById('confirm-host-styles')) {
    const s = document.createElement('style')
    s.id = 'confirm-host-styles'
    s.textContent = `
      dialog#confirm-host-root::backdrop {
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
      }
      @keyframes flashPopIn {
        0%   { opacity: 0; transform: scale(0.94) translateY(6px); }
        100% { opacity: 1; transform: scale(1)    translateY(0); }
      }
    `
    document.head.appendChild(s)
  }

  window.confirmTest = () => useConfirm()({
    title: 'Test Confirm',
    message: 'This is a test.',
    details: ['First bullet', 'Second bullet'],
    confirmLabel: 'Do It',
    variant: 'warning',
    requireReason: true,
    reasonLabel: 'Test reason',
  }).then(r => console.log('confirm result:', r))

  window.__confirmDebug = () => {
    const host = document.getElementById('confirm-host-root')
    console.log({
      hostExists:   !!host,
      tagName:      host?.tagName,
      dialogOpen:   host?.open,
      isInTopLayer: host?.matches(':modal'),   // ← true when showModal() is active
      stateOpen:    confirmState.open,
    })
  }
}