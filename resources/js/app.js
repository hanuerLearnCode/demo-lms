import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import 'bootstrap/dist/js/bootstrap.bundle.min';

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
