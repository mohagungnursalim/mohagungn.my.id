import './bootstrap';
import 'flowbite';

import "@fortawesome/fontawesome-free/css/all.min.css";

document.addEventListener('livewire:navigated', () => {
    window.initFlowbite(); // Init Flowbite SPA navigate
});
