import './bootstrap';

import '../css/app.css'; 

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
Alpine.plugin(focus)
window.Alpine = Alpine;
Alpine.start();

// import { Html5QrcodeScanner } from "html5-qrcode";
// window.Html5QrcodeScanner = Html5QrcodeScanner;
// export default Html5QrcodeScanner

// window.Html5QrcodeScanner = require('html5-qrcode');
