<script setup lang="ts">
import { usePage, router, Link } from "@inertiajs/vue3";
import { watch, ref, computed, onMounted, onBeforeUnmount } from "vue";
import Swal from "sweetalert2";

declare global {
  interface Window {
    route: (name: string, params?: any, absolute?: boolean) => string;
  }
}

interface Advisor {
  id: number;
  advisor_name: string;
  club_role: string;
  email: string;
  profile_image?: string;
}

interface WebSettings {
  contact_email: string;
  phone_number: string;
  facebook_link: string;
  twitter_link: string;
  instagram_link: string;
  linkedin_link: string;
}

interface PageProps {
  auth?: {
    advisor?: Advisor;
  };
  flash?: {
    success?: string;
    error?: string;
  };
  webSettings: WebSettings;
}

const page = usePage<PageProps>();
const route = window.route;
const mobileMenuOpen = ref(false);
const advisorDropdownOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

// Check if advisor is authenticated
const advisor = computed(() => page.props.auth?.advisor);

const logout = () => {
  if (confirm("Are you sure you want to logout?")) {
    advisorDropdownOpen.value = false;
    router.post("/advisor/logout");
  }
};

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const toggleAdvisorDropdown = () => {
  advisorDropdownOpen.value = !advisorDropdownOpen.value;
};

const closeDropdown = () => {
  advisorDropdownOpen.value = false;
};

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
    advisorDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

watch(
  () => page.props,
  (newProps) => {
    const flash = newProps.flash;

    if (flash?.success) {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String(flash.success),
      });
    } else if (flash?.error) {
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: String(flash.error),
      });
    }
  },
  { deep: true, immediate: true }
);
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <header
      class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 shadow-xl sticky top-0 z-50 border-b-4 border-yellow-500"
    >
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo/Brand -->
          <div class="flex-shrink-0">
            <Link :href="route('home')" class="flex items-center space-x-3 group">
              <div
                class="bg-gray-900 p-1 rounded-lg group-hover:bg-gray-800 transition-all duration-300 transform group-hover:scale-105"
              >
                <img src="/assets/images/club_logo.png" alt="Club Logo" class="w-10" />
              </div>
              <div class="hidden sm:block">
                <div
                  class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-gray-800 transition-colors"
                >
                  MBSTU
                </div>
                <div class="text-xs text-gray-700 font-medium -mt-1">Science Club</div>
              </div>
            </Link>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-1">
            <!-- Public Links -->
            <Link
              :href="route('home')"
              class="text-base font-semibold text-gray-700 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-slate-700 hover:text-white"
              :class="$page.component == 'Home' ? 'text-white bg-slate-700 shadow-md' : ''"
            >
              Home
            </Link>
            <Link
              href="/#contact"
              class="text-base font-semibold text-gray-700 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-slate-700 hover:text-white"
            >
              Contact
            </Link>
            <Link
              :href="route('aboutUs')"
              class="text-base font-semibold text-gray-700 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-slate-700 hover:text-white"
              :class="
                $page.component == 'About' ? 'text-white bg-slate-700 shadow-md' : ''
              "
            >
              About
            </Link>
            <Link
              :href="route('application')"
              class="text-base font-semibold text-gray-700 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-slate-700 hover:text-white"
              :class="
                $page.component == 'Applications/ApplyPage'
                  ? 'text-white bg-slate-700 shadow-md'
                  : ''
              "
            >
              Apply Now
            </Link>
            <Link
              :href="route('track_page')"
              class="text-base font-semibold text-gray-700 transition-all duration-200 px-4 py-2 rounded-lg hover:bg-slate-700 hover:text-white"
              :class="
                $page.component == 'Applications/TrackApplication'
                  ? 'text-white bg-slate-700 shadow-md'
                  : ''
              "
            >
              My Application
            </Link>

            <!-- Advisor Dropdown (if authenticated) -->
            <div v-if="advisor" class="relative ml-4" ref="dropdownRef">
              <button
                @click="toggleAdvisorDropdown"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-yellow-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-900"
                :class="
                  advisorDropdownOpen ? 'bg-yellow-500/30 ring-2 ring-gray-900' : ''
                "
              >
                <div class="relative">
                  <img
                    v-if="advisor.profile_image"
                    :src="`/storage/${advisor.profile_image}`"
                    :alt="advisor.advisor_name"
                    class="w-10 h-10 rounded-full object-cover border-3 border-gray-900 shadow-md"
                  />
                  <div
                    v-else
                    class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center border-3 border-gray-800 shadow-md"
                  >
                    <span class="text-yellow-400 font-bold text-lg">{{
                      advisor.advisor_name.charAt(0).toUpperCase()
                    }}</span>
                  </div>
                  <div
                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-yellow-400 rounded-full"
                  ></div>
                </div>
                <div class="hidden lg:block text-left">
                  <div class="text-sm font-bold text-gray-900">
                    {{ advisor.advisor_name }}
                  </div>
                  <div class="text-xs text-gray-600 capitalize">
                    {{ advisor.club_role }}
                  </div>
                </div>
                <svg
                  class="w-5 h-5 text-gray-700 transition-transform duration-200"
                  :class="advisorDropdownOpen ? 'rotate-180' : ''"
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

              <!-- Dropdown Menu -->
              <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
              >
                <div
                  v-if="advisorDropdownOpen"
                  class="absolute right-0 mt-3 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden"
                >
                  <div class="bg-gradient-to-r from-yellow-400 to-yellow-300 px-4 py-3">
                    <div class="text-sm font-bold text-gray-900 truncate">
                      {{ advisor.advisor_name }}
                    </div>
                    <div class="text-xs text-gray-700 truncate">
                      {{ advisor.email }}
                    </div>
                  </div>
                  <div class="py-2">
                    <Link
                      href="/advisor/dashboard"
                      @click="closeDropdown"
                      class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-yellow-50 hover:text-gray-900 transition-colors"
                      :class="
                        $page.component == 'advisor_panel/Index'
                          ? 'bg-yellow-100 text-gray-900'
                          : ''
                      "
                    >
                      <svg
                        class="w-5 h-5 mr-3 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                      </svg>
                      Dashboard
                    </Link>
                    <Link
                      href="/advisor/profile"
                      @click="closeDropdown"
                      class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-yellow-50 hover:text-gray-900 transition-colors"
                      :class="
                        $page.component == 'advisor_panel/Profile'
                          ? 'bg-yellow-100 text-gray-900'
                          : ''
                      "
                    >
                      <svg
                        class="w-5 h-5 mr-3 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                      </svg>
                      My Profile
                    </Link>
                    <div class="border-t border-gray-200 my-1"></div>
                    <button
                      @click="logout"
                      class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-700 hover:bg-red-50 hover:text-red-900 transition-colors"
                    >
                      <svg
                        class="w-5 h-5 mr-3 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                      </svg>
                      Logout
                    </button>
                  </div>
                </div>
              </Transition>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden">
            <button
              @click="toggleMobileMenu"
              type="button"
              class="inline-flex items-center justify-center p-2 rounded-lg text-gray-900 hover:bg-yellow-500/30 focus:outline-none focus:ring-2 focus:ring-gray-900 transition-all"
              aria-expanded="false"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                v-if="!mobileMenuOpen"
                class="block h-7 w-7"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2.5"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
              <svg
                v-else
                class="block h-7 w-7"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2.5"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile menu -->
        <Transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-1"
        >
          <div
            v-if="mobileMenuOpen"
            class="md:hidden pb-4 border-t border-yellow-500 mt-2"
          >
            <div class="space-y-1 pt-4">
              <!-- Advisor Info (Mobile) -->
              <div
                v-if="advisor"
                class="flex items-center space-x-3 px-3 py-3 mb-3 bg-yellow-500/20 rounded-lg"
              >
                <img
                  v-if="advisor.profile_image"
                  :src="`/storage/${advisor.profile_image}`"
                  :alt="advisor.advisor_name"
                  class="w-12 h-12 rounded-full object-cover border-2 border-gray-900"
                />
                <div
                  v-else
                  class="w-12 h-12 rounded-full bg-gray-900 flex items-center justify-center border-2 border-gray-800"
                >
                  <span class="text-yellow-400 font-bold text-xl">{{
                    advisor.advisor_name.charAt(0).toUpperCase()
                  }}</span>
                </div>
                <div>
                  <div class="text-sm font-bold text-gray-900">
                    {{ advisor.advisor_name }}
                  </div>
                  <div class="text-xs text-gray-700">Advisor Account</div>
                </div>
              </div>

              <!-- Public Links -->
              <Link
                :href="route('home')"
                @click="mobileMenuOpen = false"
                class="block text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-yellow-500/30 px-4 py-3 rounded-lg transition-colors"
                :class="
                  $page.component == 'Home' ? 'text-gray-900 bg-yellow-500 shadow-md' : ''
                "
              >
                Home
              </Link>
              <Link
                :href="route('application')"
                @click="mobileMenuOpen = false"
                class="block text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-yellow-500/30 px-4 py-3 rounded-lg transition-colors"
                :class="
                  $page.component == 'Applications/ApplyPage'
                    ? 'text-gray-900 bg-yellow-500 shadow-md'
                    : ''
                "
              >
                Apply Now
              </Link>
              <Link
                :href="route('track_page')"
                @click="mobileMenuOpen = false"
                class="block text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-yellow-500/30 px-4 py-3 rounded-lg transition-colors"
                :class="
                  $page.component == 'Applications/TrackApplication'
                    ? 'text-gray-900 bg-yellow-500 shadow-md'
                    : ''
                "
              >
                My Application
              </Link>

              <!-- Advisor Links (Mobile) -->
              <template v-if="advisor">
                <div class="border-t border-yellow-500 my-3"></div>
                <div
                  class="px-4 py-2 text-xs font-bold text-gray-600 uppercase tracking-wider"
                >
                  Advisor Panel
                </div>
                <Link
                  href="/advisor/dashboard"
                  @click="mobileMenuOpen = false"
                  class="flex items-center text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-yellow-500/30 px-4 py-3 rounded-lg transition-colors"
                  :class="
                    $page.component == 'advisor_panel/Index'
                      ? 'text-gray-900 bg-yellow-500 shadow-md'
                      : ''
                  "
                >
                  <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                  </svg>
                  Dashboard
                </Link>
                <Link
                  href="/advisor/profile"
                  @click="mobileMenuOpen = false"
                  class="flex items-center text-base font-semibold text-gray-700 hover:text-gray-900 hover:bg-yellow-500/30 px-4 py-3 rounded-lg transition-colors"
                  :class="
                    $page.component == 'advisor_panel/Profile'
                      ? 'text-gray-900 bg-yellow-500 shadow-md'
                      : ''
                  "
                >
                  <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                  My Profile
                </Link>
                <button
                  @click="logout"
                  class="w-full flex items-center text-base font-semibold text-red-700 hover:text-red-900 hover:bg-red-50 px-4 py-3 rounded-lg transition-colors mt-2"
                >
                  <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                    />
                  </svg>
                  Logout
                </button>
              </template>
            </div>
          </div>
        </Transition>
      </nav>
    </header>

    <main class="flex-grow bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100">
      <slot />
    </main>

    <footer
      class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white shadow-2xl mt-auto border-t-4 border-yellow-400"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
          <!-- About Section -->
          <div class="space-y-4">
            <div class="flex items-center space-x-2">
              <div class="bg-yellow-400 p-2 rounded-lg">
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
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                  />
                </svg>
              </div>
              <h3 class="text-xl font-black text-yellow-400">MBSTUSC</h3>
            </div>
            <p class="text-sm text-gray-300 leading-relaxed">
              Mawlana Bhashani Science and Technology University Science Club - Empowering
              minds through science and innovation.
            </p>
          </div>

          <!-- Quick Links -->
          <div class="space-y-4">
            <h3
              class="text-lg font-bold text-yellow-400 border-b border-yellow-400/30 pb-2"
            >
              Quick Links
            </h3>
            <ul class="space-y-3 text-sm">
              <li>
                <Link
                  :href="route('privacy')"
                  class="text-gray-300 hover:text-yellow-400 transition-colors flex items-center group"
                >
                  <svg
                    class="w-4 h-4 mr-2 text-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    />
                  </svg>
                  Privacy Policy
                </Link>
              </li>
              <li>
                <Link
                  :href="route('terms_conditions')"
                  class="text-gray-300 hover:text-yellow-400 transition-colors flex items-center group"
                >
                  <svg
                    class="w-4 h-4 mr-2 text-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    />
                  </svg>
                  Terms & Conditions
                </Link>
              </li>
              <li>
                <Link
                  :href="route('code_of_conduct')"
                  class="text-gray-300 hover:text-yellow-400 transition-colors flex items-center group"
                >
                  <svg
                    class="w-4 h-4 mr-2 text-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    />
                  </svg>
                  Code Of Conduct
                </Link>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div class="space-y-4">
            <h3
              class="text-lg font-bold text-yellow-400 border-b border-yellow-400/30 pb-2"
            >
              Contact Us
            </h3>
            <ul class="space-y-3 text-sm text-gray-300">
              <li class="flex items-start">
                <svg
                  class="w-5 h-5 mr-3 text-yellow-400 flex-shrink-0 mt-0.5"
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
                  :href="'mailto:' + page.props.webSettings.contact_email"
                  class="hover:text-yellow-400 transition-colors"
                >
                  {{ page.props.webSettings.contact_email }}
                </a>
              </li>
              <li class="flex items-start">
                <svg
                  class="w-5 h-5 mr-3 text-yellow-400 flex-shrink-0 mt-0.5"
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
                <a
                  :href="'tel:' + page.props.webSettings.phone_number"
                  class="hover:text-yellow-400 transition-colors"
                >
                  {{ page.props.webSettings.phone_number }}
                </a>
              </li>
              <li class="flex items-start">
                <svg
                  class="w-5 h-5 mr-3 text-yellow-400 flex-shrink-0 mt-0.5"
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
                <span>MBSTU, Tangail</span>
              </li>
            </ul>
          </div>

          <!-- Social Media -->
          <div class="space-y-4">
            <h3
              class="text-lg font-bold text-yellow-400 border-b border-yellow-400/30 pb-2"
            >
              Follow Us
            </h3>
            <div class="flex space-x-4">
              <a
                :href="page.props.webSettings.facebook_link"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-gray-800 p-3 rounded-lg text-gray-300 hover:text-yellow-400 hover:bg-gray-700 transition-all duration-300 transform hover:scale-110"
                aria-label="Facebook"
              >
                <svg
                  class="w-6 h-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                  />
                </svg>
              </a>
              <a
                :href="page.props.webSettings.twitter_link"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-gray-800 p-3 rounded-lg text-gray-300 hover:text-yellow-400 hover:bg-gray-700 transition-all duration-300 transform hover:scale-110"
                aria-label="Twitter"
              >
                <svg
                  class="w-6 h-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
                  />
                </svg>
              </a>
              <a
                :href="page.props.webSettings.instagram_link"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-gray-800 p-3 rounded-lg text-gray-300 hover:text-pink-500 hover:bg-gray-700 transition-all duration-300 transform hover:scale-110"
                aria-label="Instagram"
              >
                <svg
                  class="w-6 h-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                  />
                </svg>
              </a>
              <a
                :href="page.props.webSettings.linkedin_link"
                target="_blank"
                rel="noopener noreferrer"
                class="bg-gray-800 p-3 rounded-lg text-gray-300 hover:text-yellow-400 hover:bg-gray-700 transition-all duration-300 transform hover:scale-110"
                aria-label="LinkedIn"
              >
                <svg
                  class="w-6 h-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                  />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-10 pt-8 text-center">
          <p class="text-sm text-gray-400">
            © {{ new Date().getFullYear() }} MBSTUSC. All rights reserved. |
            <span class="text-yellow-400">Empowering Science & Innovation</span>
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
button {
  cursor: pointer;
}
</style>
