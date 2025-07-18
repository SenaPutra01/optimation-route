import "./bootstrap";

import.meta.glob(["../assets/images/**", "../assets/fonts/**"]);

import { Html5Qrcode } from "html5-qrcode";

import Alpine from "alpinejs";

window.Alpine = Alpine;
window.Html5Qrcode = Html5Qrcode;

Alpine.start();
// Html5Qrcode.start();
