import './bootstrap';

import '../css/app.css'; 

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
Alpine.plugin(focus)
window.Alpine = Alpine;
Alpine.start();

