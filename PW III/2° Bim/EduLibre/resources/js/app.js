import './bootstrap';
import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

window.Alpine = Alpine;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

Alpine.start();

// Importa módulos de animação por página
import './animations/home';
import './animations/catalog';
import './animations/player';