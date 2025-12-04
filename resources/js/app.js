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
    camera.position.set(0, 1, 8); // Cámara más alejada para evitar recortes
    camera.lookAt(0, 0, 0);

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    // Iluminación mejorada
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.0);
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffe066, 3); // Luz dorada más intensa
    dirLight.position.set(5, 5, 5);
    scene.add(dirLight);

    const spotLight = new THREE.SpotLight(0xffffff, 5);
    spotLight.position.set(0, 10, 0);
    spotLight.angle = Math.PI / 4;
    spotLight.penumbra = 0.5;
    scene.add(spotLight);

    // Grupo del Trofeo
    const trophyGroup = new THREE.Group();

    // Material Dorado Mejorado
    const goldMaterial = new THREE.MeshStandardMaterial({
        color: 0xffd700,
        metalness: 1,
        roughness: 0.15, // Más brillante
        emissive: 0x332200,
        emissiveIntensity: 0.2
    });

    // Base
    const baseGeo = new THREE.CylinderGeometry(1.2, 1.4, 0.3, 32);
    const base = new THREE.Mesh(baseGeo, goldMaterial);
    base.position.y = -1.5;
    trophyGroup.add(base);

    const baseGeo2 = new THREE.CylinderGeometry(0.8, 1.2, 0.3, 32);
    const base2 = new THREE.Mesh(baseGeo2, goldMaterial);
    base2.position.y = -1.2;
    trophyGroup.add(base2);

    // Tallo
    const stemGeo = new THREE.CylinderGeometry(0.3, 0.5, 1.5, 32);
    const stem = new THREE.Mesh(stemGeo, goldMaterial);
    stem.position.y = -0.3;
    trophyGroup.add(stem);

    // Copa
    const cupGeo = new THREE.SphereGeometry(1.5, 32, 32, 0, Math.PI * 2, 0, Math.PI / 2);
    const cup = new THREE.Mesh(cupGeo, goldMaterial);
    cup.rotation.x = Math.PI;
    cup.position.y = 1.2;
    trophyGroup.add(cup);

    // Asas (Torus)
    const handleGeo = new THREE.TorusGeometry(0.8, 0.1, 16, 100, Math.PI);

    const handle1 = new THREE.Mesh(handleGeo, goldMaterial);
    handle1.position.set(-1.5, 1.2, 0);
    handle1.rotation.z = Math.PI / 2;
    trophyGroup.add(handle1);

    const handle2 = new THREE.Mesh(handleGeo, goldMaterial);
    handle2.position.set(1.5, 1.2, 0);
    handle2.rotation.z = -Math.PI / 2;
    trophyGroup.add(handle2);

    // Inclinar el trofeo ligeramente
    trophyGroup.rotation.z = 0.1;
    trophyGroup.rotation.x = 0.1;

    scene.add(trophyGroup);

    // Animación
    gsap.to(trophyGroup.rotation, {
        y: Math.PI * 2 + 0.1, // Mantener el offset
        duration: 12,
        ease: "none",
        repeat: -1
    });

    gsap.to(trophyGroup.position, {
        y: 0.3,
        duration: 2.5,
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

    // Crear textura circular difuminada programáticamente
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
// ============================================
// ANIMACIÓN DEL LIBRO
// ============================================
function initBookAnimation() {
    if (!document.getElementById('book-section')) return;

    gsap.registerPlugin(ScrollTrigger);

    const bookSection = document.getElementById('book-section');
    const bookContainer = document.getElementById('book-container');
    const bookCover = document.getElementById('book-cover');
    const bookPages = document.getElementById('book-pages');
    const slides = gsap.utils.toArray('.book-slide');

    if (!bookContainer || !bookCover || !bookPages) return;

    // Inicializar estado
    gsap.set(bookCover, { rotationY: 0, transformOrigin: "right center", transformStyle: "preserve-3d" });
    gsap.set(bookPages, { rotationY: 0, x: 0, transformOrigin: "left center", transformStyle: "preserve-3d" });

    // Ocultar todos los slides excepto el primero inicialmente
    gsap.set(slides, { opacity: 0 });
    gsap.set(slides[0], { opacity: 1 });

    // Timeline principal con Pinning
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: "#book-section",
            start: "top top", // Empezar cuando la sección toque el top
            end: "+=3000", // Duración del scroll pinned (3000px)
            pin: true, // Pinea la sección
            scrub: 1,
            markers: false,
            anticipatePin: 1
        }
    });

    // 1. Abrir el libro
    tl.to(bookContainer, { y: 0, duration: 1, ease: "power2.out" })
        .to(bookCover, { rotationY: -180, duration: 2, ease: "power2.inOut" }, "<") // Abrir completamente
        .to(bookPages, { rotationY: 0, duration: 2, ease: "power2.out" }, "<0.5")
        .to(bookCover, { boxShadow: "0 0 50px rgba(255, 215, 0, 0.3)", duration: 1 }, "<");

    // 2. Ciclo de Slides (Admin -> Juez -> Participante)
    // Slide 1 ya está visible.

    // 2. Ciclo de Slides (Efecto de pasar página)
    // Inicializar slides: todos en origen, pero con rotación si es necesario
    gsap.set(slides, { transformOrigin: "left center", backfaceVisibility: "hidden" });

    // Transición a Slide 2 (Juez)
    // Slide 1 se voltea hacia la izquierda (desaparece)
    // Slide 2 entra (ya estaba ahí, solo aseguramos visibilidad)

    // Animación más compleja: Simular pasar página
    // Slide 1 se va
    tl.to(slides[0], { opacity: 0, rotationY: -90, duration: 1, ease: "power2.in" }, "+=0.5")
        .fromTo(slides[1],
            { opacity: 0, rotationY: 90 },
            { opacity: 1, rotationY: 0, duration: 1, ease: "power2.out" }, "<0.5"); // Solapamiento para efecto fluido

    // Transición a Slide 3 (Participante)
    tl.to(slides[1], { opacity: 0, rotationY: -90, duration: 1, ease: "power2.in" }, "+=1")
        .fromTo(slides[2],
            { opacity: 0, rotationY: 90 },
            { opacity: 1, rotationY: 0, duration: 1, ease: "power2.out" }, "<0.5");

    // Esperar un poco en el último slide
    tl.to({}, { duration: 1 });

    // 3. Animación de Salida (Desaparición completa)
    tl.to(bookSection, {
        opacity: 0,
        scale: 0.9,
        filter: "blur(10px)",
        duration: 2,
        ease: "power2.in"
    });

    console.log('Book animation initialized with pinning and slides');
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