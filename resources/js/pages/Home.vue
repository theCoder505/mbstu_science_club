<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { ref, onMounted, onUnmounted, computed } from "vue";
import * as THREE from "three";
import axios from "axios";
import Swal from "sweetalert2";

interface Advisor {
  id: number;
  advisor_name: string;
  email: string;
  department: string;
  designation: string;
  club_role: string;
  profile_image: string;
  facebook?: string;
  twitter?: string;
  linkedin?: string;
  research_gate?: string;
  google_scholar?: string;
  portfolio?: string;
}

interface TeamMember {
  id: number;
  teammate_image: string;
  teammate_name: string;
  department: string;
  designation: string;
  membership_period: string;
  small_desc: string;
  facebook_link?: string;
  linkedin_link?: string;
}

interface Committee {
  id: number;
  committee_name: string;
  committee_period: string;
  committee_images: string[];
}

interface FAQ {
  id: number;
  question: string;
  answer: string;
}

interface GalleryImage {
  id: number;
  image_path: string;
  status: string;
  position: number;
}

interface WebSettings {
  id: number;
  president_name: string;
  president_sign: string;
  facebook_link?: string;
  twitter_link?: string;
  instagram_link?: string;
  linkedin_link?: string;
  contact_email: string;
  phone_number: string;
}

interface ContactForm {
  email: string;
  subject: string;
  message_text: string;
  otp: string;
}

const props = withDefaults(
  defineProps<{
    canRegister?: boolean;
    advisors: Advisor[];
    team: TeamMember[];
    committees: Committee[];
    faqs: FAQ[];
    gallery: GalleryImage[];
    website: WebSettings[];
    active_members: string;
    event_organised: string;
    research_projects: string;
    awards_won: string;
    join_club_form: string;
    facebookPageUrl: string;
    facebook_page_iframe: string;
  }>(),
  {
    canRegister: true,
  }
);

const canvasRef = ref<HTMLCanvasElement | null>(null);
let scene: THREE.Scene;
let camera: THREE.PerspectiveCamera;
let renderer: THREE.WebGLRenderer;
let animationId: number;
let particles: THREE.Points;

const openFaqIndex = ref<number | null>(null);
const selectedGalleryImage = ref<string | null>(null);
const currentImageIndex = ref(0);
const currentGalleryType = ref<"gallery" | "committee">("gallery");
const selectedCommitteeImages = ref<string[]>([]);

const statsCounters = ref({
  members: 0,
  events: 0,
  research: 0,
  awards: 0,
});

const targetStats = {
  members: parseInt(props.active_members),
  events: parseInt(props.event_organised),
  research: parseInt(props.research_projects),
  awards: parseInt(props.awards_won),
};

const contactForm = ref<ContactForm>({
  email: "",
  subject: "",
  message_text: "",
  otp: "",
});
const showOtpField = ref(false);
const otpSent = ref(false);
const sendingEmail = ref(false);
const sendingOtp = ref(false);

const initThreeJS = () => {
  if (!canvasRef.value) return;

  scene = new THREE.Scene();
  scene.background = new THREE.Color(0x0a0e1a);

  camera = new THREE.PerspectiveCamera(
    75,
    canvasRef.value.clientWidth / canvasRef.value.clientHeight,
    0.1,
    1000
  );
  camera.position.z = 30;

  renderer = new THREE.WebGLRenderer({
    canvas: canvasRef.value,
    antialias: true,
    alpha: true,
  });
  renderer.setSize(canvasRef.value.clientWidth, canvasRef.value.clientHeight);
  renderer.setPixelRatio(window.devicePixelRatio);

  createDNAHelix();
  createParticles();
  createAtomStructure();
  animate();

  window.addEventListener("resize", handleResize);
};

const createDNAHelix = () => {
  const helixGroup = new THREE.Group();
  const helixHeight = 20;
  const helixRadius = 3;
  const segments = 40;

  for (let i = 0; i < segments; i++) {
    const angle = (i / segments) * Math.PI * 4;
    const y = (i / segments) * helixHeight - helixHeight / 2;

    const geometry1 = new THREE.SphereGeometry(0.2, 16, 16);
    const material1 = new THREE.MeshPhongMaterial({
      color: 0xfbbf24,
      emissive: 0xfbbf24,
      emissiveIntensity: 0.3,
    });
    const sphere1 = new THREE.Mesh(geometry1, material1);
    sphere1.position.set(Math.cos(angle) * helixRadius, y, Math.sin(angle) * helixRadius);
    helixGroup.add(sphere1);

    const geometry2 = new THREE.SphereGeometry(0.2, 16, 16);
    const material2 = new THREE.MeshPhongMaterial({
      color: 0x14b8a6,
      emissive: 0x14b8a6,
      emissiveIntensity: 0.3,
    });
    const sphere2 = new THREE.Mesh(geometry2, material2);
    sphere2.position.set(
      Math.cos(angle + Math.PI) * helixRadius,
      y,
      Math.sin(angle + Math.PI) * helixRadius
    );
    helixGroup.add(sphere2);

    if (i > 0) {
      const points = [sphere1.position, sphere2.position];
      const lineGeometry = new THREE.BufferGeometry().setFromPoints(points);
      const lineMaterial = new THREE.LineBasicMaterial({
        color: 0x64748b,
        opacity: 0.4,
        transparent: true,
      });
      const line = new THREE.Line(lineGeometry, lineMaterial);
      helixGroup.add(line);
    }
  }

  helixGroup.position.x = -15;
  helixGroup.rotation.x = Math.PI / 6;
  scene.add(helixGroup);
  (helixGroup as any).userData.rotate = true;
};

const createParticles = () => {
  const particlesGeometry = new THREE.BufferGeometry();
  const particleCount = 1000;
  const positions = new Float32Array(particleCount * 3);

  for (let i = 0; i < particleCount * 3; i += 3) {
    positions[i] = (Math.random() - 0.5) * 100;
    positions[i + 1] = (Math.random() - 0.5) * 100;
    positions[i + 2] = (Math.random() - 0.5) * 100;
  }

  particlesGeometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));

  const particlesMaterial = new THREE.PointsMaterial({
    color: 0xfbbf24,
    size: 0.1,
    transparent: true,
    opacity: 0.6,
  });

  particles = new THREE.Points(particlesGeometry, particlesMaterial);
  scene.add(particles);
};

const createAtomStructure = () => {
  const atomGroup = new THREE.Group();

  const nucleusGeometry = new THREE.SphereGeometry(1, 32, 32);
  const nucleusMaterial = new THREE.MeshPhongMaterial({
    color: 0xfbbf24,
    emissive: 0xfbbf24,
    emissiveIntensity: 0.5,
  });
  const nucleus = new THREE.Mesh(nucleusGeometry, nucleusMaterial);
  atomGroup.add(nucleus);

  const orbitCount = 3;
  for (let i = 0; i < orbitCount; i++) {
    const orbitRadius = 2 + i * 1.5;
    const orbitGeometry = new THREE.TorusGeometry(orbitRadius, 0.02, 16, 100);
    const orbitMaterial = new THREE.MeshBasicMaterial({
      color: 0x14b8a6,
      transparent: true,
      opacity: 0.3,
    });
    const orbit = new THREE.Mesh(orbitGeometry, orbitMaterial);
    orbit.rotation.x = Math.random() * Math.PI;
    orbit.rotation.y = Math.random() * Math.PI;
    atomGroup.add(orbit);

    const electronGeometry = new THREE.SphereGeometry(0.2, 16, 16);
    const electronMaterial = new THREE.MeshPhongMaterial({
      color: 0x14b8a6,
      emissive: 0x14b8a6,
      emissiveIntensity: 0.5,
    });
    const electron = new THREE.Mesh(electronGeometry, electronMaterial);
    electron.position.x = orbitRadius;
    orbit.add(electron);
  }

  atomGroup.position.x = 15;
  atomGroup.position.y = 5;
  scene.add(atomGroup);
  (atomGroup as any).userData.rotate = true;
};

const animate = () => {
  animationId = requestAnimationFrame(animate);

  scene.children.forEach((child) => {
    if ((child as any).userData.rotate) {
      child.rotation.y += 0.005;
    }
  });

  if (particles) {
    particles.rotation.y += 0.0005;
    particles.rotation.x += 0.0003;
  }

  renderer.render(scene, camera);
};

const handleResize = () => {
  if (!canvasRef.value) return;
  camera.aspect = canvasRef.value.clientWidth / canvasRef.value.clientHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(canvasRef.value.clientWidth, canvasRef.value.clientHeight);
};

const animateCounter = (target: number, key: keyof typeof statsCounters.value) => {
  const duration = 2000;
  const increment = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      statsCounters.value[key] = target;
      clearInterval(timer);
    } else {
      statsCounters.value[key] = Math.floor(current);
    }
  }, 16);
};

const toggleFaq = (index: number) => {
  openFaqIndex.value = openFaqIndex.value === index ? null : index;
};

const openGalleryModal = (index: number) => {
  currentImageIndex.value = index;
  currentGalleryType.value = "gallery";
  selectedGalleryImage.value = props.gallery[index].image_path;
};

const openCommitteeImageModal = (images: string[], index: number) => {
  selectedCommitteeImages.value = images;
  currentImageIndex.value = index;
  currentGalleryType.value = "committee";
  selectedGalleryImage.value = images[index];
};

const closeGalleryModal = () => {
  selectedGalleryImage.value = null;
  selectedCommitteeImages.value = [];
};

const nextImage = () => {
  if (currentGalleryType.value === "gallery") {
    currentImageIndex.value = (currentImageIndex.value + 1) % props.gallery.length;
    selectedGalleryImage.value = props.gallery[currentImageIndex.value].image_path;
  } else {
    currentImageIndex.value =
      (currentImageIndex.value + 1) % selectedCommitteeImages.value.length;
    selectedGalleryImage.value = selectedCommitteeImages.value[currentImageIndex.value];
  }
};

const prevImage = () => {
  if (currentGalleryType.value === "gallery") {
    currentImageIndex.value =
      (currentImageIndex.value - 1 + props.gallery.length) % props.gallery.length;
    selectedGalleryImage.value = props.gallery[currentImageIndex.value].image_path;
  } else {
    currentImageIndex.value =
      (currentImageIndex.value - 1 + selectedCommitteeImages.value.length) %
      selectedCommitteeImages.value.length;
    selectedGalleryImage.value = selectedCommitteeImages.value[currentImageIndex.value];
  }
};

const totalImages = computed(() => {
  return currentGalleryType.value === "gallery"
    ? props.gallery.length
    : selectedCommitteeImages.value.length;
});

const sendOTP = async () => {
  Swal.fire({
    title: "Sending OTP...",
    text: "Please wait while we send you a 6 digit OTP",
    allowOutsideClick: false,
    showConfirmButton: false,
    willOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    await axios.post("/email-otp", {
      email: contactForm.value.email,
    });

    Swal.close();

    Swal.fire({
      icon: "success",
      title: "Success!",
      text: String("OTP Sent, Verify Now!"),
    });

    showOtpField.value = true;
    otpSent.value = true;
  } catch (error) {
    console.log(error);
  }
};

const submitContactForm = async () => {
  if (
    !contactForm.value.email ||
    !contactForm.value.subject ||
    !contactForm.value.message_text ||
    !contactForm.value.otp
  ) {
    Swal.fire({
      icon: "error",
      title: "Missing Information",
      text: "Please fill all fields including OTP",
      confirmButtonColor: "#fbbf24",
    });
  }

  if (contactForm.value.otp.length !== 6 || !/^\d+$/.test(contactForm.value.otp)) {
    Swal.fire({
      icon: "error",
      title: "Invalid OTP",
      text: "Please enter a valid 6-digit OTP",
      confirmButtonColor: "#fbbf24",
    });
  }

  try {
    sendingEmail.value = true;

    Swal.fire({
      title: "Sending Message...",
      text: "Please wait while we send your message",
      allowOutsideClick: false,
      showConfirmButton: false,
      willOpen: () => {
        Swal.showLoading();
      },
    });

    await axios.post("/verify-and-send-email", contactForm.value);

    Swal.close();

    Swal.fire({
      icon: "success",
      title: "Message Sent Successfully!",
      html: `
        <div class="text-center">
          <div class="mb-4">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-lg mb-2">Your message has been sent successfully!</p>
          <p class="text-gray-600 text-sm">We'll get back to you as soon as possible.</p>
        </div>
      `,
      confirmButtonColor: "#14b8a6",
      confirmButtonText: "Great!",
    });

    // Reset form
    contactForm.value = {
      email: "",
      subject: "",
      message_text: "",
      otp: "",
    };
    showOtpField.value = false;
    otpSent.value = false;
  } catch (error: any) {
    Swal.close();

    let errorMessage = "Failed to send message. Please try again.";
    if (error.response?.data?.message) {
      errorMessage = error.response.data.message;
    } else if (error.response?.data?.errors) {
      // Handle validation errors
      const errors = error.response.data.errors;
      errorMessage = Object.values(errors).flat().join(", ");
    } else if (error.message === "Network Error") {
      errorMessage = "Network error. Please check your internet connection.";
    }

    Swal.fire({
      icon: "error",
      title: "Failed to Send Message",
      html: `
        <div class="text-left">
          <p class="mb-2">${errorMessage}</p>
          <p class="text-sm text-gray-600 mt-2">Please verify your OTP and try again.</p>
        </div>
      `,
      confirmButtonColor: "#fbbf24",
    });
  } finally {
    sendingEmail.value = false;
  }
};

const handleContactSubmit = async () => {
  console.log(otpSent.value);
  if (otpSent.value == false) {
    await sendOTP();
  } else {
    await submitContactForm();
  }
};

onMounted(() => {
  initThreeJS();

  const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
  scene.add(ambientLight);

  const pointLight1 = new THREE.PointLight(0xfbbf24, 1, 100);
  pointLight1.position.set(10, 10, 10);
  scene.add(pointLight1);

  const pointLight2 = new THREE.PointLight(0x14b8a6, 1, 100);
  pointLight2.position.set(-10, -10, 10);
  scene.add(pointLight2);

  setTimeout(() => {
    animateCounter(targetStats.members, "members");
    animateCounter(targetStats.events, "events");
    animateCounter(targetStats.research, "research");
    animateCounter(targetStats.awards, "awards");
  }, 500);
});

onUnmounted(() => {
  if (animationId) {
    cancelAnimationFrame(animationId);
  }
  window.removeEventListener("resize", handleResize);
  if (renderer) {
    renderer.dispose();
  }
});
</script>

<template>
  <Head title="Welcome to MBSTUSC" />
  <WebLayout>
    <!-- Hero Section with Three.js Background -->
    <section
      class="relative min-h-screen flex items-center justify-center overflow-hidden"
    >
      <canvas
        ref="canvasRef"
        class="absolute inset-0 w-full h-full"
        style="z-index: 0"
      ></canvas>
      <div
        class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/60 to-slate-900/90"
        style="z-index: 1"
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div
          class="animate-fade-in-up"
          style="animation-delay: 0.2s; animation-fill-mode: both"
        >
          <div class="mb-8 flex justify-center">
            <img
              src="/assets/images/club_logo.png"
              alt="MBSTU Science Club Logo"
              class="h-32 w-32 md:h-40 md:w-40 object-contain filter drop-shadow-2xl"
            />
          </div>

          <h1
            class="text-5xl md:text-7xl lg:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-yellow-400 to-teal-400 mb-6 leading-tight"
          >
            MBSTU Science Club
          </h1>
          <p
            class="text-xl md:text-2xl lg:text-3xl text-gray-300 font-light mb-8 max-w-4xl mx-auto"
          >
            Empowering Minds Through Science & Innovation
          </p>
          <p class="text-lg md:text-xl text-teal-300 mb-12 max-w-3xl mx-auto">
            Join us in exploring the wonders of science, conducting groundbreaking
            research, and building a community of passionate innovators
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a
              :href="join_club_form"
              target="_blank"
              class="group relative px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-bold text-lg rounded-full overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-yellow-400/50 hover:scale-105"
            >
              <span class="relative z-10">Join Our Club</span>
              <div
                class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-teal-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              ></div>
            </a>
            <a
              href="#about"
              class="px-8 py-4 border-2 border-teal-400 text-teal-400 font-bold text-lg rounded-full hover:bg-teal-400 hover:text-gray-900 transition-all duration-300 hover:shadow-lg hover:shadow-teal-400/50"
            >
              Learn More
            </a>
          </div>
        </div>

        <div
          class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce"
        >
          <svg
            class="w-6 h-6 text-yellow-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 14l-7 7m0 0l-7-7m7 7V3"
            />
          </svg>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-gray-900 via-slate-800 to-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div
              class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-500 mb-2"
            >
              {{ statsCounters.members }}+
            </div>
            <div class="text-gray-400 font-semibold text-lg">Active Members</div>
          </div>
          <div class="text-center">
            <div
              class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-500 mb-2"
            >
              {{ statsCounters.events }}+
            </div>
            <div class="text-gray-400 font-semibold text-lg">Events Organized</div>
          </div>
          <div class="text-center">
            <div
              class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-500 mb-2"
            >
              {{ statsCounters.research }}+
            </div>
            <div class="text-gray-400 font-semibold text-lg">Research Projects</div>
          </div>
          <div class="text-center">
            <div
              class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-teal-500 mb-2"
            >
              {{ statsCounters.awards }}+
            </div>
            <div class="text-gray-400 font-semibold text-lg">Awards Won</div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-b from-slate-50 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            About MBSTUSC
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto"
          ></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="space-y-6">
            <p class="text-lg text-gray-700 leading-relaxed">
              The Mawlana Bhashani Science and Technology University Science Club
              (MBSTUSC) is a vibrant community of passionate students dedicated to
              advancing scientific knowledge and innovation.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed">
              Our club provides a platform for students to engage in hands-on research,
              participate in workshops, attend seminars, and collaborate on exciting
              projects that push the boundaries of science and technology.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed">
              Whether you're interested in physics, chemistry, biology, computer science,
              or any other scientific field, MBSTUSC offers opportunities for growth,
              learning, and making lasting connections with like-minded individuals.
            </p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div
              class="bg-gradient-to-br from-yellow-400 to-yellow-500 p-6 rounded-2xl text-gray-900 transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Innovation</h3>
              <p class="text-sm">Fostering creative solutions</p>
            </div>

            <div
              class="bg-gradient-to-br from-teal-400 to-teal-500 p-6 rounded-2xl text-white transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Research</h3>
              <p class="text-sm">Advancing scientific knowledge</p>
            </div>

            <div
              class="bg-gradient-to-br from-teal-500 to-teal-600 p-6 rounded-2xl text-white transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Community</h3>
              <p class="text-sm">Building lasting connections</p>
            </div>

            <div
              class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-2xl text-gray-900 transform hover:scale-105 transition-transform duration-300"
            >
              <svg
                class="w-12 h-12 mb-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                />
              </svg>
              <h3 class="font-bold text-xl mb-2">Excellence</h3>
              <p class="text-sm">Striving for the best</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Club History Section -->
    <section class="py-24 bg-gradient-to-b from-white to-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Our Journey
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            A legacy of scientific excellence and innovation
          </p>
        </div>

        <div class="relative">
          <div
            class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-yellow-400 to-teal-400 hidden md:block"
          ></div>

          <div class="space-y-12">
            <div class="grid md:grid-cols-2 gap-8 items-center">
              <div class="md:text-right">
                <div
                  class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border-l-4 border-yellow-400"
                >
                  <h3 class="text-2xl font-bold text-gray-900 mb-3">Foundation</h3>
                  <p class="text-gray-700 leading-relaxed">
                    MBSTU Science Club was established in 2019 by a group of passionate
                    students who envisioned creating a platform for scientific exploration
                    and innovation. The club began with a small group of dedicated members
                    committed to promoting science education.
                  </p>
                </div>
              </div>
              <div class="hidden md:block"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
              <div class="hidden md:block"></div>
              <div>
                <div
                  class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border-r-4 border-teal-400"
                >
                  <h3 class="text-2xl font-bold text-gray-900 mb-3">
                    Growth & Expansion
                  </h3>
                  <p class="text-gray-700 leading-relaxed">
                    Through dedicated efforts and successful events, the club rapidly grew
                    in membership and influence. We established partnerships with research
                    institutions and began organizing regular workshops, seminars, and
                    science fairs.
                  </p>
                </div>
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
              <div class="md:text-right">
                <div
                  class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border-l-4 border-yellow-400"
                >
                  <h3 class="text-2xl font-bold text-gray-900 mb-3">
                    National Recognition
                  </h3>
                  <p class="text-gray-700 leading-relaxed">
                    Our members' research projects gained national recognition, winning
                    prestigious awards and competitions. The club became known as a hub
                    for innovative scientific thinking and practical problem-solving.
                  </p>
                </div>
              </div>
              <div class="hidden md:block"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
              <div class="hidden md:block"></div>
              <div>
                <div
                  class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border-r-4 border-teal-400"
                >
                  <h3 class="text-2xl font-bold text-gray-900 mb-3">Present & Future</h3>
                  <p class="text-gray-700 leading-relaxed">
                    Today, MBSTUSC continues to thrive with hundreds of active members,
                    regular events, and a commitment to fostering the next generation of
                    scientists and innovators. We look forward to expanding our impact and
                    creating more opportunities for scientific excellence.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Mission, Vision & Values Section -->
    <section class="py-24 bg-slate-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text text-white mb-4"
          >
            What We Do
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto"
          ></div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
          >
            <div
              class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-gray-900"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
            <p class="text-gray-700 leading-relaxed">
              To cultivate a passion for scientific inquiry and innovation among students,
              providing them with opportunities to explore, experiment, and excel in their
              scientific pursuits while contributing to society through research and
              education.
            </p>
          </div>

          <div
            class="bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
          >
            <div
              class="w-16 h-16 bg-teal-400 rounded-full flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
            <p class="text-gray-700 leading-relaxed">
              To become the leading student-led scientific organization that inspires
              generations of innovators, researchers, and problem-solvers who will shape
              the future through groundbreaking discoveries and technological
              advancements.
            </p>
          </div>

          <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
          >
            <div
              class="w-16 h-16 bg-purple-400 rounded-full flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Values</h3>
            <ul class="text-gray-700 space-y-2">
              <li class="flex items-start">
                <span class="text-purple-600 mr-2">•</span
                ><span>Excellence in scientific research and education</span>
              </li>
              <li class="flex items-start">
                <span class="text-purple-600 mr-2">•</span
                ><span>Collaboration and teamwork</span>
              </li>
              <li class="flex items-start">
                <span class="text-purple-600 mr-2">•</span
                ><span>Innovation and creative thinking</span>
              </li>
              <li class="flex items-start">
                <span class="text-purple-600 mr-2">•</span
                ><span>Integrity and ethical conduct</span>
              </li>
              <li class="flex items-start">
                <span class="text-purple-600 mr-2">•</span
                ><span>Inclusivity and diversity</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Advisors Section -->
    <section class="py-24 bg-gradient-to-b from-white to-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Our Distinguished Advisors
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Meet the dedicated faculty members guiding our journey towards scientific
            excellence
          </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
          <div
            v-for="advisor in advisors"
            :key="advisor.id"
            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-yellow-400"
          >
            <div class="p-8">
              <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                  <div
                    class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-yellow-400 group-hover:border-teal-400 transition-colors duration-300"
                  >
                    <img
                      :src="`/storage/${advisor.profile_image}`"
                      :alt="advisor.advisor_name"
                      class="w-full h-full object-cover"
                    />
                  </div>
                </div>

                <div class="flex-1">
                  <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    {{ advisor.advisor_name }}
                  </h3>
                  <p class="text-teal-600 font-semibold mb-2">
                    {{ advisor.designation }}
                  </p>
                  <p class="text-gray-600 mb-1">Department of {{ advisor.department }}</p>
                  <p
                    class="inline-block px-3 py-1 bg-yellow-400 text-gray-900 font-semibold text-sm rounded-full"
                  >
                    {{ advisor.club_role }}
                  </p>
                </div>
              </div>

              <div class="mt-6 flex items-center gap-2">
                <svg
                  class="w-5 h-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <a
                  :href="`mailto:${advisor.email}`"
                  class="text-gray-600 hover:text-teal-600 transition-colors"
                  >{{ advisor.email }}</a
                >
              </div>

              <div class="mt-4 flex gap-3">
                <a
                  v-if="advisor.linkedin"
                  :href="advisor.linkedin"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.research_gate"
                  :href="advisor.research_gate"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M19.586 0c-.818 0-1.508.19-2.073.565-.563.377-.97.936-1.213 1.68a3.193 3.193 0 0 0-.112.437 8.365 8.365 0 0 0-.078.53 9 9 0 0 0-.05.727c-.01.282-.013.621-.013 1.016a31.121 31.121 0 0 0 .014 1.017 9 9 0 0 0 .05.727 7.946 7.946 0 0 0 .077.53c.025.153.063.306.112.437.245.744.65 1.303 1.214 1.68.565.376 1.256.564 2.074.564.817 0 1.508-.188 2.073-.565.564-.376.97-.935 1.213-1.68.049-.13.087-.283.112-.436a8.046 8.046 0 0 0 .078-.531 8.96 8.96 0 0 0 .05-.726c.01-.283.013-.622.013-1.017a31.121 31.121 0 0 0-.014-1.016 9 9 0 0 0-.05-.727 7.946 7.946 0 0 0-.077-.53 2.628 2.628 0 0 0-.112-.437c-.245-.744-.65-1.303-1.214-1.68-.565-.377-1.256-.565-2.074-.565zM8.857 5.857c-.818 0-1.508.19-2.073.565-.563.377-.97.936-1.213 1.68a3.193 3.193 0 0 0-.112.437 8.365 8.365 0 0 0-.078.53 9 9 0 0 0-.05.727c-.01.282-.013.621-.013 1.016a31.121 31.121 0 0 0 .014 1.017 9 9 0 0 0 .05.727 7.946 7.946 0 0 0 .077.53c.025.153.063.306.112.437.245.744.65 1.303 1.214 1.68.565.376 1.256.564 2.074.564.817 0 1.508-.188 2.073-.565.564-.376.97-.935 1.213-1.68.049-.13.087-.283.112-.436a8.046 8.046 0 0 0 .078-.531 8.96 8.96 0 0 0 .05-.726c.01-.283.013-.622.013-1.017a31.121 31.121 0 0 0-.014-1.016 9 9 0 0 0-.05-.727 7.946 7.946 0 0 0-.077-.53 2.628 2.628 0 0 0-.112-.437c-.245-.744-.65-1.303-1.214-1.68-.565-.377-1.256-.565-2.074-.565z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.google_scholar"
                  :href="advisor.google_scholar"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M5.242 13.769L0 9.5 12 0l12 9.5-5.242 4.269C17.548 11.249 14.978 9.5 12 9.5c-2.977 0-5.548 1.748-6.758 4.269zM12 10a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"
                    />
                  </svg>
                </a>
                <a
                  v-if="advisor.portfolio"
                  :href="advisor.portfolio"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-teal-400 hover:text-white transition-all duration-300"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section v-if="team.length > 0" class="py-24 bg-gradient-to-b from-slate-50 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Our Legacy Team
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Honoring the pioneers who shaped our club's foundation
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <Link
            v-for="member in team"
            :key="member.id"
            :href="'/teammate/' + member.id + '/' + member.teammate_name"
            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden"
          >
            <div class="relative h-64 overflow-hidden">
              <img
                :src="`/storage/${member.teammate_image}`"
                :alt="member.teammate_name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60"
              ></div>
              <div class="absolute bottom-4 left-4 right-4">
                <h3 class="text-2xl font-bold text-white mb-1">
                  {{ member.teammate_name }}
                </h3>
                <p class="text-yellow-400 font-semibold">{{ member.designation }}</p>
              </div>
            </div>

            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">{{ member.department }}</span>
                <span
                  class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold"
                  >{{ member.membership_period }}</span
                >
              </div>
              <p class="text-gray-700 text-sm leading-relaxed">{{ member.small_desc }}</p>

              <div class="mt-4 flex gap-2">
                <a
                  v-if="member.facebook_link"
                  :href="member.facebook_link"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    />
                  </svg>
                </a>
                <a
                  v-if="member.linkedin_link"
                  :href="member.linkedin_link"
                  target="_blank"
                  class="p-2 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                    />
                  </svg>
                </a>
              </div>
            </div>
          </Link>
        </div>
      </div>
    </section>

    <!-- Committees Section -->
    <section
      v-if="committees.length > 0"
      class="py-24 bg-gradient-to-b from-white to-slate-50"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Executive Committees
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Our leadership through the years
          </p>
        </div>

        <!-- Grid layout for committees -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="committee in committees"
            :key="committee.id"
            @click="openCommitteeImageModal(committee.committee_images, 0)"
            class="group bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-2xl transition-all duration-300"
          >
            <!-- Single image display -->
            <div class="relative h-64 overflow-hidden">
              <img
                :src="`/storage/${committee.committee_images[0]}`"
                :alt="committee.committee_name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center"
              >
                <div class="text-center">
                  <svg
                    class="w-12 h-12 text-white mx-auto mb-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                    />
                  </svg>
                  <p class="text-white text-sm font-semibold">
                    +{{ committee.committee_images.length - 1 }} more
                  </p>
                </div>
              </div>
            </div>

            <!-- Committee info -->
            <div class="p-6">
              <h3 class="text-2xl font-bold text-gray-900 mb-2">
                {{ committee.committee_name }}
              </h3>
              <span
                class="inline-block px-4 py-2 bg-gradient-to-r from-yellow-400 to-teal-400 text-gray-900 font-bold rounded-full text-sm"
              >
                {{ committee.committee_period }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Facebook Posts & FAQs Section -->
    <section class="py-24 bg-yellow-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Latest Updates & FAQs
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Stay connected with our latest activities and find answers to common questions
          </p>
        </div>

        <div class="grid grid-cols-1 items-center lg:grid-cols-2 gap-8 lg:gap-12">
          <div class="">
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center lg:text-left">
                Frequently Asked Questions
              </h3>
              <p class="text-gray-600 mb-6 text-center lg:text-left">
                Find answers to common questions about joining and participating
              </p>
            </div>

            <div class="space-y-4">
              <div
                v-for="(faq, index) in faqs"
                :key="faq.id"
                class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-transparent hover:border-yellow-400 transition-colors duration-300"
              >
                <button
                  @click="toggleFaq(index)"
                  class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition-colors"
                >
                  <span class="text-lg font-bold text-gray-900 pr-4">
                    {{ faq.question }}
                  </span>
                  <svg
                    class="w-6 h-6 text-teal-600 transition-transform duration-300 flex-shrink-0"
                    :class="openFaqIndex === index ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    />
                  </svg>
                </button>

                <Transition
                  enter-active-class="transition-all ease-out duration-300"
                  enter-from-class="max-h-0 opacity-0"
                  enter-to-class="max-h-96 opacity-100"
                  leave-active-class="transition-all ease-in duration-200"
                  leave-from-class="max-h-96 opacity-100"
                  leave-to-class="max-h-0 opacity-0"
                >
                  <div v-if="openFaqIndex === index" class="px-6 pb-4">
                    <p class="text-gray-700 leading-relaxed">{{ faq.answer }}</p>
                  </div>
                </Transition>
              </div>
            </div>

            <!-- No FAQs Message -->
            <div v-if="faqs.length === 0" class="text-center py-12">
              <div class="mb-4">
                <svg
                  class="w-16 h-16 text-gray-400 mx-auto"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <h4 class="text-xl font-semibold text-gray-700 mb-2">No FAQs Available</h4>
              <p class="text-gray-600">
                Check back later for frequently asked questions.
              </p>
            </div>
          </div>

          <div class="">
            <div class="sticky top-8">
              <div class="relative w-full">
                <div
                  v-if="facebook_page_iframe"
                  class="bg-gray-50 rounded-xl p-6 border border-gray-200"
                  v-html="facebook_page_iframe"
                ></div>
              </div>

              <div class="text-center lg:text-left mt-6">
                <a
                  :href="facebookPageUrl"
                  target="_blank"
                  class="inline-flex items-center gap-3 px-6 py-3 bg-[#1877f2] text-white font-bold text-lg rounded-full hover:bg-[#0c63d4] transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/50 transform hover:scale-105"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    />
                  </svg>
                  Follow on Facebook
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Section -->
    <section v-if="gallery.length > 0" class="py-24 bg-yellow-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-700 mb-4"
          >
            Our Gallery
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Capturing moments of innovation, collaboration, and discovery
          </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div
            v-for="(image, index) in gallery"
            :key="image.id"
            @click="openGalleryModal(index)"
            class="group relative overflow-hidden rounded-xl cursor-pointer aspect-square"
          >
            <img
              :src="`/storage/${image.image_path}`"
              :alt="`Gallery image ${index + 1}`"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery/Committee Modal -->
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="selectedGalleryImage"
        @click="closeGalleryModal"
        class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
      >
        <button
          @click="closeGalleryModal"
          class="absolute top-4 right-4 text-white hover:text-yellow-400 transition-colors z-10"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>

        <button
          @click.stop="prevImage"
          class="absolute left-4 text-white hover:text-yellow-400 transition-colors z-10"
        >
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            />
          </svg>
        </button>

        <img
          :src="`/storage/${selectedGalleryImage}`"
          @click.stop
          class="max-w-full max-h-[90vh] object-contain rounded-lg"
          alt="Gallery image"
        />

        <button
          @click.stop="nextImage"
          class="absolute right-4 text-white hover:text-yellow-400 transition-colors z-10"
        >
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </button>

        <div class="absolute bottom-4 text-white text-center">
          <p class="text-sm">{{ currentImageIndex + 1 }} / {{ totalImages }}</p>
        </div>
      </div>
    </Transition>

    <!-- Contact Section -->
    <section class="py-24 bg-slate-900" id="contact">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2
            class="text-4xl md:text-5xl font-black text-transparent bg-clip-text text-white mb-4"
          >
            Get In Touch
          </h2>
          <div
            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-teal-400 mx-auto mb-6"
          ></div>
          <p class="text-lg text-gray-200 max-w-3xl mx-auto">
            Have questions? We'd love to hear from you. Send us a message and we'll
            respond as soon as possible.
          </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
          <!-- Contact Form -->
          <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-0">Send Us a Message</h3>

            <p class="text-green-500 mb-4">
              Once you verify your email address with OTP then you can send us message!
            </p>

            <form @submit.prevent="handleContactSubmit" class="space-y-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Email Address</label
                >
                <input
                  v-model="contactForm.email"
                  type="email"
                  required
                  :disabled="otpSent"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-400 focus:outline-none transition-colors disabled:bg-gray-100"
                  placeholder="your.email@example.com"
                />
              </div>

              <div v-if="otpSent">
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Subject</label
                >
                <input
                  v-model="contactForm.subject"
                  type="text"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-400 focus:outline-none transition-colors"
                  placeholder="What is this about?"
                />
              </div>

              <div v-if="otpSent">
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Message</label
                >
                <textarea
                  v-model="contactForm.message_text"
                  required
                  rows="5"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-400 focus:outline-none transition-colors resize-none"
                  placeholder="Tell us what you'd like to know..."
                ></textarea>
              </div>

              <div v-if="showOtpField">
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >OTP Code</label
                >
                <input
                  v-model="contactForm.otp"
                  type="text"
                  required
                  maxlength="6"
                  pattern="\d{6}"
                  inputmode="numeric"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-400 focus:outline-none transition-colors text-center text-xl tracking-widest font-mono"
                  placeholder="0 0 0 0 0 0"
                />
                <p class="text-sm text-gray-600 mt-2">
                  Check your email for the 6-digit OTP code we sent you.
                </p>
              </div>

              <button
                type="submit"
                :disabled="sendingOtp || sendingEmail"
                class="w-full px-6 py-4 bg-gradient-to-r from-yellow-400 to-teal-400 text-gray-900 font-bold text-lg rounded-lg hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <svg
                  v-if="sendingOtp || sendingEmail"
                  class="animate-spin h-5 w-5 text-gray-900"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                <span v-if="!otpSent">
                  {{ sendingOtp ? "Sending OTP..." : "Get OTP" }}
                </span>
                <span v-else>
                  {{ sendingEmail ? "Sending Message..." : "Send Message" }}
                </span>
              </button>
            </form>
          </div>

          <!-- Map and Contact Info -->
          <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
              <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h3>

              <div class="space-y-4">
                <div class="flex items-start gap-4">
                  <div
                    class="flex-shrink-0 w-12 h-12 bg-yellow-400 rounded-lg flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-gray-900"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                      />
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                    <a
                      :href="`mailto:${website[0]?.contact_email}`"
                      class="text-teal-600 hover:text-teal-700"
                      >{{ website[0]?.contact_email }}</a
                    >
                  </div>
                </div>

                <div class="flex items-start gap-4">
                  <div
                    class="flex-shrink-0 w-12 h-12 bg-teal-400 rounded-lg flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-white"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                      />
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Phone</h4>
                    <a
                      :href="`tel:${website[0]?.phone_number}`"
                      class="text-teal-600 hover:text-teal-700"
                      >{{ website[0]?.phone_number }}</a
                    >
                  </div>
                </div>

                <div class="flex items-start gap-4">
                  <div
                    class="flex-shrink-0 w-12 h-12 bg-purple-400 rounded-lg flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-white"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-1">Address</h4>
                    <p class="text-gray-600">
                      Mawlana Bhashani Science and Technology University<br />Santosh,
                      Tangail, Bangladesh
                    </p>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex gap-3">
                <a
                  v-if="website[0]?.facebook_link"
                  :href="website[0].facebook_link"
                  target="_blank"
                  class="p-3 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    />
                  </svg>
                </a>
                <a
                  v-if="website[0]?.linkedin_link"
                  :href="website[0].linkedin_link"
                  target="_blank"
                  class="p-3 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                    />
                  </svg>
                </a>
                <a
                  v-if="website[0]?.instagram_link"
                  :href="website[0].instagram_link"
                  target="_blank"
                  class="p-3 bg-slate-100 rounded-lg hover:bg-yellow-400 transition-colors"
                >
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                      d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                    />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Google Maps -->
            <div class="bg-white rounded-2xl shadow-lg p-4 overflow-hidden">
              <h3 class="text-2xl font-bold text-gray-900 mb-4">Find Us</h3>
              <div class="rounded-xl overflow-hidden">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3638.173409532751!2d89.88800857540514!3d24.235712978342473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fdfc7edb573571%3A0xda791ddc1ce793bd!2sMawlana%20Bhashani%20Science%20and%20Technology%20University!5e0!3m2!1sen!2sbd!4v1767431875667!5m2!1sen!2sbd"
                  width="100%"
                  height="300"
                  style="border: 0"
                  allowfullscreen="true"
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  class="w-full"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section
      class="py-24 bg-gradient-to-r from-gray-900 via-slate-800 to-gray-900 relative overflow-hidden"
    >
      <div
        class="absolute inset-0 opacity-10"
        style="
          background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        "
      ></div>

      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
          Already A Part Of The
          <span
            class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-teal-400"
            >Science Revolution?</span
          >
        </h2>
        <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
          If you are a part of our Science Club and a dedicated member with your
          scientific excellence, innovation and made a real difference in the world; you
          can apply for your membership certificate and once you are approved, you will
          get the certificate via email
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <Link
            href="/application"
            class="group relative px-10 py-5 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-bold text-xl rounded-full overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-yellow-400/50 hover:scale-105"
          >
            <span class="relative z-10 flex items-center">
              Get Certificate
              <svg
                class="w-6 h-6 ml-2 group-hover:translate-x-1 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6"
                />
              </svg>
            </span>
          </Link>

          <Link
            href="/track-certificate"
            class="px-10 py-5 border-2 border-teal-400 text-teal-400 font-bold text-xl rounded-full hover:bg-teal-400 hover:text-gray-900 transition-all duration-300 hover:shadow-lg hover:shadow-teal-400/50"
          >
            Track Certificate
          </Link>
        </div>
      </div>
    </section>
  </WebLayout>
</template>

<style scoped>
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}

* {
  scroll-behavior: smooth;
}

::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #1e293b;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #fbbf24, #14b8a6);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #f59e0b, #0d9488);
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}

* {
  scroll-behavior: smooth;
}

::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #1e293b;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #fbbf24, #14b8a6);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #f59e0b, #0d9488);
}

button {
  cursor: pointer;
}
</style>
