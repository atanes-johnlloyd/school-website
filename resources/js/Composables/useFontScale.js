// resources/js/Composables/useFontScale.js
import { ref, onMounted } from 'vue'

const currentScale = ref('font-scale-normal')

export function useFontScale() {
  const setFontScale = (scaleClass) => {
    const root = document.documentElement
    // Remove previous scale classes
    root.classList.remove('font-scale-sm', 'font-scale-normal', 'font-scale-lg', 'font-scale-xl')
    
    // Apply chosen scale class
    root.classList.add(scaleClass)
    currentScale.value = scaleClass
    
    // Remember user preference across page loads
    localStorage.setItem('user_font_scale', scaleClass)
  }

  const initFontScale = () => {
    const saved = localStorage.getItem('user_font_scale') || 'font-scale-normal'
    setFontScale(saved)
  }

  onMounted(() => {
    initFontScale()
  })

  return {
    currentScale,
    setFontScale
  }
}