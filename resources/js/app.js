import './bootstrap';

// Importar GSAP y ScrollTrigger
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// Importar Three.js y GLTFLoader
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

// Registrar ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

// Exportar gsap globalmente para uso en otros scripts
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// ============================================
// CONFIGURACIÓN DE LA ESCENA 3D DEL TROFEO
// ============================================
function initTrophy3D() {
    const canvas = document.getElementById('trophy-canvas');
    if (!canvas) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
    camera.position.set(0, 1, 8);
    camera.lookAt(0, 0, 0);

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;

    // Iluminación mejorada para resaltar el dorado
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xfff0dd, 4);
    dirLight.position.set(5, 10, 7);
    dirLight.castShadow = true;
    scene.add(dirLight);

    const pointLight = new THREE.PointLight(0xffd700, 2, 10);
    pointLight.position.set(-2, 2, 2);
    scene.add(pointLight);

    // Grupo del Trofeo
    const trophyGroup = new THREE.Group();

    // Material Dorado Premium (Physical Material)
    const goldMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xffcc00,
        metalness: 1.0,
        roughness: 0.2,
        clearcoat: 1.0,
        clearcoatRoughness: 0.1,
        emissive: 0x221100,
        emissiveIntensity: 0.2,
        reflectivity: 1.0
    });

    // Base (Más detallada)
    const baseGeo = new THREE.CylinderGeometry(1.4, 1.6, 0.4, 64);
    const base = new THREE.Mesh(baseGeo, goldMaterial);
    base.position.y = -1.8;
    trophyGroup.add(base);

    const baseGeo2 = new THREE.CylinderGeometry(1.0, 1.4, 0.4, 64);
    const base2 = new THREE.Mesh(baseGeo2, goldMaterial);
    base2.position.y = -1.4;
    trophyGroup.add(base2);

    // Tallo (Con curvas)
    const stemGeo = new THREE.CylinderGeometry(0.4, 0.6, 1.8, 32);
    const stem = new THREE.Mesh(stemGeo, goldMaterial);
    stem.position.y = -0.3;
    trophyGroup.add(stem);

    // Anillo decorativo en el tallo
    const ringGeo = new THREE.TorusGeometry(0.5, 0.1, 16, 100);
    const ring = new THREE.Mesh(ringGeo, goldMaterial);
    ring.rotation.x = Math.PI / 2;
    ring.position.y = -0.3;
    trophyGroup.add(ring);

    // Copa (Más suave)
    const cupGeo = new THREE.SphereGeometry(1.6, 64, 64, 0, Math.PI * 2, 0, Math.PI * 0.65);
    const cup = new THREE.Mesh(cupGeo, goldMaterial);
    cup.rotation.x = Math.PI;
    cup.position.y = 1.3;
    cup.material.side = THREE.DoubleSide;
    trophyGroup.add(cup);

    // Asas (Más elegantes)
    const handlePath = new THREE.Path();
    handlePath.moveTo(0, 0);
    handlePath.bezierCurveTo(1.5, 0.5, 1.5, 2.5, 0, 3);

    const handleGeo = new THREE.TubeGeometry(
        new THREE.CatmullRomCurve3([
            new THREE.Vector3(1.4, 0.5, 0),
            new THREE.Vector3(2.2, 1.5, 0),
            new THREE.Vector3(1.4, 2.5, 0)
        ]),
        64, 0.15, 16, false
    );

    const handle1 = new THREE.Mesh(handleGeo, goldMaterial);
    trophyGroup.add(handle1);

    const handle2 = new THREE.Mesh(handleGeo, goldMaterial);
    handle2.rotation.y = Math.PI;
    trophyGroup.add(handle2);

    // Inclinación inicial
    trophyGroup.rotation.z = 0.15;
    trophyGroup.rotation.x = 0.1;

    scene.add(trophyGroup);

    // Animación de flotación y rotación
    gsap.to(trophyGroup.rotation, {
        y: Math.PI * 2 + 0.15,
        duration: 15,
        ease: "none",
        repeat: -1
    });

    gsap.to(trophyGroup.position, {
        y: 0.4,
        duration: 3,
        ease: "sine.inOut",
        yoyo: true,
        repeat: -1
    });

    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        const width = canvas.clientWidth;
        const height = canvas.clientHeight;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    });
}

// ============================================
// PARTÍCULAS DE FONDO (COPOS DE NIEVE DIFUMINADOS)
// ============================================
function initParticles() {
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 5;

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);

    const canvasTexture = document.createElement('canvas');
    canvasTexture.width = 32;
    canvasTexture.height = 32;
    const ctx = canvasTexture.getContext('2d');
    const gradient = ctx.createRadialGradient(16, 16, 0, 16, 16, 16);
    gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, 32, 32);
    const texture = new THREE.CanvasTexture(canvasTexture);

    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 100;
    const posArray = new Float32Array(particlesCount * 3);

    for (let i = 0; i < particlesCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 15;
    }

    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.2,
        map: texture,
        transparent: true,
        opacity: 0.6,
        depthWrite: false,
        blending: THREE.AdditiveBlending
    });

    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);

    function animate() {
        requestAnimationFrame(animate);
        particlesMesh.rotation.y += 0.001;
        particlesMesh.rotation.x += 0.0005;
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}

// ============================================
// ANIMACIÓN DEL LIBRO
// ============================================
function initBookAnimation() {
    if (!document.getElementById('book-section')) return;

    gsap.registerPlugin(ScrollTrigger);

    const bookContainer = document.getElementById('book-container');
    const bookCover = document.getElementById('book-cover');
    const page1 = document.getElementById('page-1');
    const page2 = document.getElementById('page-2');
    const bookControls = document.getElementById('book-controls');
    const bookText = document.getElementById('book-text');

    if (!bookContainer || !bookCover || !page1 || !page2) return;

    // 1. CONFIGURACIÓN INICIAL (Estado Hero)
    // El libro empieza fijo en el fondo, inclinado y semitransparente
    // Ajustado para estar "detrás" del trofeo visualmente
    gsap.set(bookContainer, {
        position: 'fixed',
        top: '20%', // Un poco más arriba para asomar detrás del trofeo
        left: '50%',
        xPercent: -50,
        yPercent: -50,
        rotationX: 30,
        rotationY: -10,
        rotationZ: -5,
        scale: 0.5, // Más pequeño al inicio
        opacity: 0, // Empieza invisible para evitar flash
        zIndex: 0,
        filter: 'blur(4px)'
    });

    // Configuración de páginas y cubierta para rotación realista
    const pages = [bookCover, page1, page2];
    gsap.set(pages, {
        rotationY: 0,
        transformOrigin: "left center",
        transformStyle: "preserve-3d"
    });

    // Aparecer suavemente en el Hero (Fade In inicial)
    gsap.to(bookContainer, { opacity: 0.4, duration: 1, delay: 0.5 });

    // 2. FASE 1: TRANSICIÓN HERO -> BOOK SECTION
    // Al hacer scroll, el libro viaja, se endereza y se vuelve opaco
    const transitionTl = gsap.timeline({
        scrollTrigger: {
            trigger: "#book-section",
            start: "top bottom",
            end: "center center",
            scrub: 1,
            immediateRender: false
        }
    });

    transitionTl
        .to(bookContainer, {
            top: '50%',
            rotationX: 0,
            rotationY: 0,
            rotationZ: 0,
            scale: 1,
            opacity: 1,
            filter: 'blur(0px)',
            zIndex: 40,
            ease: "power2.inOut"
        })
        .to([bookText, bookControls], { opacity: 1, duration: 0.5 }, "-=0.5");

    // 3. FASE 2: APERTURA Y NAVEGACIÓN (PINNED)
    const bookTl = gsap.timeline({
        scrollTrigger: {
            trigger: "#book-section",
            start: "center center",
            end: "+=4000", // Scroll largo para leer cómodamente
            pin: true,
            scrub: 1,
            anticipatePin: 1
        }
    });

    // Paso A: Abrir la Portada
    bookTl.to(bookCover, {
        rotationY: -180,
        duration: 2,
        ease: "power2.inOut"
    });
    // Eliminado el desplazamiento xPercent para mantener el lomo centrado

    // Paso B: Pasar Página 1 (Admin -> Juez)
    bookTl.to(page1, {
        rotationY: -180,
        duration: 2,
        ease: "power2.inOut"
    }, "+=0.5");

    // Paso C: Pasar Página 2 (Participante -> Final)
    bookTl.to(page2, {
        rotationY: -180,
        duration: 2,
        ease: "power2.inOut"
    }, "+=0.5");

    // Mantener el final un momento
    bookTl.to({}, { duration: 1 });

    // 4. FASE 3: SALIDA
    bookTl.to(bookContainer, {
        opacity: 0,
        scale: 1.1,
        filter: 'blur(10px)',
        duration: 1.5,
        ease: "power2.in"
    });

    console.log('Realistic Book Animation Initialized');
    ScrollTrigger.refresh();
}

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar animación del libro
    initBookAnimation();

    // Inicializar escena 3D del Trofeo
    initTrophy3D();

    // Inicializar Partículas
    initParticles();
});