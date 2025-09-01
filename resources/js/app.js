import './bootstrap';
import 'flowbite';

import "@fortawesome/fontawesome-free/css/all.min.css";

// Store global untuk theme
Alpine.store('theme', {
  dark: localStorage.getItem('dark') === 'true',
  toggle() {
    this.dark = !this.dark;
    localStorage.setItem('dark', this.dark);
    // sinkron ke <html class="dark">
    document.documentElement.classList.toggle('dark', this.dark);
  },
});

// Pastikan sinkron tiap kali Livewire SPA navigate
document.addEventListener('livewire:navigated', () => {
    window.initFlowbite(); // Init Flowbite SPA navigate

    // 🔑 re-apply dark mode setelah navigasi
    const dark = localStorage.getItem('dark') === 'true';
    document.documentElement.classList.toggle('dark', dark);

    // kalau pakai Alpine store
    if (Alpine.store('theme')) {
        Alpine.store('theme').dark = dark;
    }
});

