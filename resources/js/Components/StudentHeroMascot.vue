<template>
  <div class="student-hero-mascot" aria-hidden="true">
    <span class="mascot-sparkle mascot-sparkle-one">✦</span>
    <span class="mascot-sparkle mascot-sparkle-two">✦</span>
    <span class="mascot-speech">{{ note }}</span>
    <img :src="catImg" alt="" class="mascot-image" />
  </div>
</template>

<script setup>
import catImg from '@/../assets/img/cat_login.png'

defineProps({
  note: {
    type: String,
    default: "You've got this!",
  },
})
</script>

<style scoped>
.student-hero-mascot {
  position: absolute;
  right: 1rem;
  bottom: 0;
  z-index: 20;
  display: none;
  width: 9rem;
  height: 10rem;
  pointer-events: none;
  animation: mascot-pop-in 850ms cubic-bezier(0.16, 1, 0.3, 1) 180ms both;
}

.mascot-image {
  position: absolute;
  right: 0;
  bottom: -0.35rem;
  width: 100%;
  height: 100%;
  object-fit: contain;
  filter: drop-shadow(0 12px 12px rgb(0 0 0 / 28%));
  transform-origin: bottom center;
  animation: mascot-float 3.2s ease-in-out 1s infinite;
}

.mascot-speech {
  position: absolute;
  top: 0.5rem;
  right: 4rem;
  z-index: 1;
  width: 12rem;
  border: 1px solid rgb(255 255 255 / 80%);
  border-radius: 1rem;
  background: #fff;
  padding: 0.45rem 0.75rem;
  color: #004d08;
  font-size: 0.65rem;
  font-weight: 800;
  line-height: 1.35;
  text-align: center;
  box-shadow: 0 8px 20px rgb(0 0 0 / 18%);
  opacity: 0;
  animation: speech-pop-in 500ms cubic-bezier(0.16, 1, 0.3, 1) 800ms both;
}

.mascot-speech::after {
  position: absolute;
  right: 0.75rem;
  bottom: -0.35rem;
  width: 0.65rem;
  height: 0.65rem;
  border-right: 1px solid rgb(255 255 255 / 80%);
  border-bottom: 1px solid rgb(255 255 255 / 80%);
  background: #fff;
  content: "";
  transform: rotate(45deg);
}

.mascot-sparkle {
  position: absolute;
  z-index: 1;
  color: #fcd34d;
  font-size: 1.15rem;
  text-shadow: 0 2px 8px rgb(0 0 0 / 20%);
  animation: sparkle-twinkle 1.8s ease-in-out infinite;
}

.mascot-sparkle-one {
  top: 2.8rem;
  left: 0.3rem;
}

.mascot-sparkle-two {
  top: 0.7rem;
  right: 1rem;
  animation-delay: 900ms;
}

@keyframes mascot-pop-in {
  0% {
    opacity: 0;
    transform: translateY(3rem) scale(0.45) rotate(-12deg);
  }

  72% {
    opacity: 1;
    transform: translateY(-0.35rem) scale(1.08) rotate(3deg);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1) rotate(0);
  }
}

@keyframes mascot-float {
  0%,
  100% {
    transform: translateY(0) rotate(0);
  }

  50% {
    transform: translateY(-0.5rem) rotate(2deg);
  }
}

@keyframes speech-pop-in {
  0% {
    opacity: 0;
    transform: translateY(0.5rem) scale(0.8);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes sparkle-twinkle {
  0%,
  100% {
    opacity: 0.55;
    transform: scale(0.8) rotate(-12deg);
  }

  50% {
    opacity: 1;
    transform: scale(1.15) rotate(12deg);
  }
}

@media (min-width: 1280px) {
  .student-hero-mascot {
    display: block;
    width: 9rem;
    height: 10rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  .student-hero-mascot,
  .mascot-image,
  .mascot-speech,
  .mascot-sparkle {
    animation: none;
  }

  .student-hero-mascot,
  .mascot-speech {
    opacity: 1;
  }
}
</style>
