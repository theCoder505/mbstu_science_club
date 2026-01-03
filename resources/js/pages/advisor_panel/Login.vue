<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

withDefaults(
  defineProps<{
    errors?: Record<string, string>;
    status?: string;
  }>(),
  {
    errors: () => ({}),
    status: "",
  }
);

const showPassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post('/advisor/login', {
    preserveScroll: true
  });
};
</script>

<template>
  <Head title="Login As Advisor" />
  <WebLayout>
    <div class="min-h-screen py-12 bg-slate-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <h3 class="text-4xl font-bold text-yellow-500 mb-2">Welcome To MBSTUSC</h3>
          <p class="text-gray-200 text-lg">Please sign in to continue</p>
        </div>

        <div class="flex justify-center">
          <div class="w-full max-w-2xl">
            <!-- Status Message -->
            <div v-if="status" class="mb-6">
              <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ status }}
              </div>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
              <!-- Card Header -->
              <div class="bg-gradient-to-r from-yellow-300 to-yellow-400 px-6 py-8">
                <h2 class="text-2xl font-bold text-center">
                  Advisor & Moderator Portal
                </h2>
                <p class="text-gray-700 text-center mt-2">
                  Access your dashboard from here
                </p>
              </div>

              <!-- Card Body -->
              <div class="px-8 py-8">
                <!-- Validation Errors -->
                <div v-if="Object.keys(errors).length > 0" class="mb-6">
                  <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div v-if="errors.email" class="text-sm">{{ errors.email }}</div>
                    <div v-if="errors.password" class="text-sm">{{ errors.password }}</div>
                  </div>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit">
                  <!-- Email Input -->
                  <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                      Email Address
                    </label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                      </div>
                      <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                        placeholder="advisor@example.com"
                      />
                    </div>
                  </div>

                  <!-- Password Input -->
                  <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                      Password
                    </label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                      </div>
                      <input
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        class="pl-10 pr-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                        placeholder="••••••••"
                      />
                      <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                      >
                        <svg v-if="!showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <!-- Remember Me & Forgot Password -->
                  <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                      <input
                        id="remember"
                        v-model="form.remember"
                        type="checkbox"
                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                      />
                      <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                      </label>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="cursor-pointer w-full bg-gradient-to-r from-yellow-300 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-xl font-semibold uppercase text-gray-900"
                  >
                    <span v-if="form.processing" class="flex items-center justify-center">
                      <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Signing in...
                    </span>
                    <span v-else>
                      Login
                    </span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </WebLayout>
</template>