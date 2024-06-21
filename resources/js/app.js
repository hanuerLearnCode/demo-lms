import 'jquery';
import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import 'bootstrap/dist/js/bootstrap.bundle.min';
import '@fortawesome/fontawesome-free/js/fontawesome.min.js';


window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
