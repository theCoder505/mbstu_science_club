<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

interface Advisor {
  id: number;
  advisor_name: string;
  email: string;
  department: string;
  designation: string;
  club_role?: string;
  facebook?: string;
  twitter?: string;
  linkedin?: string;
  google_scholar?: string;
  research_gate?: string;
  portfolio?: string;
  profile_image?: string;
  signature?: string;
}

const props = defineProps<{
  advisor: Advisor;
}>();

const form = useForm({
  advisor_name: props.advisor.advisor_name,
  email: props.advisor.email,
  department: props.advisor.department,
  designation: props.advisor.designation,
  facebook: props.advisor.facebook || "",
  twitter: props.advisor.twitter || "",
  linkedin: props.advisor.linkedin || "",
  google_scholar: props.advisor.google_scholar || "",
  research_gate: props.advisor.research_gate || "",
  portfolio: props.advisor.portfolio || "",
  password: "",
  password_confirmation: "",
  profile_image: null as File | null,
  signature: null as File | null,
});

const profileImagePreview = ref<string | null>(null);
const signaturePreview = ref<string | null>(null);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const currentProfileImage = computed(() => {
  if (profileImagePreview.value) return profileImagePreview.value;
  if (props.advisor.profile_image) return `/storage/${props.advisor.profile_image}`;
  return null;
});

const currentSignature = computed(() => {
  if (signaturePreview.value) return signaturePreview.value;
  if (props.advisor.signature) return `/storage/${props.advisor.signature}`;
  return null;
});

const handleProfileImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    form.profile_image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      profileImagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const handleSignatureChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    form.signature = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      signaturePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const triggerProfileImageUpload = () => {
  const input = document.getElementById("profile_image") as HTMLInputElement;
  input?.click();
};

const triggerSignatureUpload = () => {
  const input = document.getElementById("signature") as HTMLInputElement;
  input?.click();
};

const submit = () => {
  form.post("/advisor/update-profile", {
    onSuccess: () => {
      form.reset("password", "password_confirmation");
      profileImagePreview.value = null;
      signaturePreview.value = null;
    },
  });
};
</script>

<template>
  <Head title="Advisor Profile" />
  <WebLayout>
    <div class="min-h-screen bg-slate-900 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h3 class="font-bold text-3xl text-yellow-500">Advisor Profile</h3>
            <p class="text-gray-300 mt-2">Manage your profile information</p>
          </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="px-6 py-4 bg-gradient-to-r from-yellow-300 to-yellow-400">
            <h4 class="text-xl font-semibold text-gray-900">Profile Information</h4>
          </div>

          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Profile Image and Signature Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Profile Image -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Profile Image
                </label>
                <div class="flex flex-col items-center">
                  <div
                    @click="triggerProfileImageUpload"
                    class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:border-yellow-500 transition overflow-hidden"
                  >
                    <img
                      v-if="currentProfileImage"
                      :src="currentProfileImage"
                      alt="Profile"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="text-center text-gray-400">
                      <svg
                        class="mx-auto h-12 w-12"
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
                      <p class="mt-2 text-sm">Click to upload</p>
                    </div>
                  </div>
                  <input
                    type="file"
                    id="profile_image"
                    @change="handleProfileImageChange"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="hidden"
                  />
                  <p class="mt-2 text-xs text-gray-500">JPG, PNG, GIF up to 20MB</p>
                </div>
              </div>

              <!-- Signature -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Signature 
                </label>
                <div class="flex flex-col items-center">
                  <div
                    @click="triggerSignatureUpload"
                    class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:border-yellow-500 transition overflow-hidden bg-white"
                  >
                    <img
                      v-if="currentSignature"
                      :src="currentSignature"
                      alt="Signature"
                      class="w-full h-full object-contain p-2"
                    />
                    <div v-else class="text-center text-gray-400">
                      <svg
                        class="mx-auto h-12 w-12"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                        />
                      </svg>
                      <p class="mt-2 text-sm">Click to upload</p>
                    </div>
                  </div>
                  <input
                    type="file"
                    id="signature"
                    @change="handleSignatureChange"
                    accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                    class="hidden"
                  />
                  <p class="mt-2 text-xs text-gray-500 text-center">
                  JPG, PNG, GIF, SVG up to 20MB
                  <br>
                  <small class="text-green-500 text-sm">(* Please use a background less image thus over certificate it fits perfectly *)</small>
                  </p>
                </div>
                
              </div>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label
                  for="advisor_name"
                  class="block text-sm font-medium text-gray-700 mb-2"
                >
                  Full Name *
                </label>
                <input
                  type="text"
                  id="advisor_name"
                  v-model="form.advisor_name"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
                <p v-if="form.errors.advisor_name" class="mt-1 text-sm text-red-600">
                  {{ form.errors.advisor_name }}
                </p>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  Email Address *
                </label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                  {{ form.errors.email }}
                </p>
              </div>

              <div>
                <label
                  for="department"
                  class="block text-sm font-medium text-gray-700 mb-2"
                >
                  Department *
                </label>
                <input
                  type="text"
                  id="department"
                  v-model="form.department"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
                <p v-if="form.errors.department" class="mt-1 text-sm text-red-600">
                  {{ form.errors.department }}
                </p>
              </div>

              <div>
                <label
                  for="designation"
                  class="block text-sm font-medium text-gray-700 mb-2"
                >
                  Designation *
                </label>
                <input
                  type="text"
                  id="designation"
                  v-model="form.designation"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
                <p v-if="form.errors.designation" class="mt-1 text-sm text-red-600">
                  {{ form.errors.designation }}
                </p>
              </div>
            </div>

            <!-- Social Links -->
            <div class="border-t pt-6">
              <h5 class="text-lg font-semibold text-gray-900 mb-4">Social Links</h5>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label
                    for="facebook"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Facebook
                  </label>
                  <input
                    type="url"
                    id="facebook"
                    v-model="form.facebook"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://facebook.com/username"
                  />
                  <p v-if="form.errors.facebook" class="mt-1 text-sm text-red-600">
                    {{ form.errors.facebook }}
                  </p>
                </div>

                <div>
                  <label
                    for="twitter"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Twitter
                  </label>
                  <input
                    type="url"
                    id="twitter"
                    v-model="form.twitter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://twitter.com/username"
                  />
                  <p v-if="form.errors.twitter" class="mt-1 text-sm text-red-600">
                    {{ form.errors.twitter }}
                  </p>
                </div>

                <div>
                  <label
                    for="linkedin"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    LinkedIn
                  </label>
                  <input
                    type="url"
                    id="linkedin"
                    v-model="form.linkedin"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://linkedin.com/in/username"
                  />
                  <p v-if="form.errors.linkedin" class="mt-1 text-sm text-red-600">
                    {{ form.errors.linkedin }}
                  </p>
                </div>

                <div>
                  <label
                    for="google_scholar"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Google Scholar
                  </label>
                  <input
                    type="url"
                    id="google_scholar"
                    v-model="form.google_scholar"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://scholar.google.com/..."
                  />
                  <p v-if="form.errors.google_scholar" class="mt-1 text-sm text-red-600">
                    {{ form.errors.google_scholar }}
                  </p>
                </div>

                <div>
                  <label
                    for="research_gate"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Research Gate
                  </label>
                  <input
                    type="url"
                    id="research_gate"
                    v-model="form.research_gate"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://researchgate.net/profile/..."
                  />
                  <p v-if="form.errors.research_gate" class="mt-1 text-sm text-red-600">
                    {{ form.errors.research_gate }}
                  </p>
                </div>

                <div>
                  <label
                    for="portfolio"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Portfolio
                  </label>
                  <input
                    type="url"
                    id="portfolio"
                    v-model="form.portfolio"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="https://yourwebsite.com"
                  />
                  <p v-if="form.errors.portfolio" class="mt-1 text-sm text-red-600">
                    {{ form.errors.portfolio }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Password Change -->
            <div class="border-t pt-6">
              <h5 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h5>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label
                    for="password"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    New Password
                  </label>
                  <div class="relative">
                    <input
                      :type="showPassword ? 'text' : 'password'"
                      id="password"
                      v-model="form.password"
                      class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                      placeholder="Leave blank to keep current"
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                      <svg
                        v-if="!showPassword"
                        class="h-5 w-5 text-gray-400"
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
                      <svg
                        v-else
                        class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                        />
                      </svg>
                    </button>
                  </div>
                  <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                    {{ form.errors.password }}
                  </p>
                </div>

                <div>
                  <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Confirm Password
                  </label>
                  <div class="relative">
                    <input
                      :type="showPasswordConfirmation ? 'text' : 'password'"
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                      placeholder="Confirm your new password"
                    />
                    <button
                      type="button"
                      @click="showPasswordConfirmation = !showPasswordConfirmation"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                      <svg
                        v-if="!showPasswordConfirmation"
                        class="h-5 w-5 text-gray-400"
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
                      <svg
                        v-else
                        class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                        />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t">
              <button
                type="submit"
                :disabled="form.processing"
                class="px-6 py-3 bg-gradient-to-r from-yellow-300 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 text-gray-900 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="form.processing" class="flex items-center">
                  <svg
                    class="animate-spin -ml-1 mr-3 h-5 w-5"
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
                  Updating...
                </span>
                <span v-else>Update Profile</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </WebLayout>
</template>


<style scoped>
button{
    cursor: pointer;
}
</style>
