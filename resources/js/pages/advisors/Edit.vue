<script setup lang="ts">
import Input from "@/components/Input.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { Eye, EyeOffIcon, ArrowLeft, Pencil, User, FileText } from "lucide-vue-next";
import { ref, onMounted } from "vue";
import Swal from "sweetalert2";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Advisor & Moderators",
    href: "/advisors-and-moderators",
  },
  {
    title: "Edit",
    href: "#",
  },
];

const props = defineProps<{
  advisor: {
    id: number;
    advisor_name: string;
    email: string;
    department: string;
    designation: string;
    club_role: "Advisor" | "Moderator";
    facebook?: string;
    twitter?: string;
    linkedin?: string;
    google_scholar?: string;
    research_gate?: string;
    portfolio?: string;
    profile_image?: string;
    signature?: string;
  };
}>();

interface FormData {
  advisor_id: number;
  advisor_name: string;
  email: string;
  department: string;
  designation: string;
  club_role: "Advisor" | "Moderator" | string;
  facebook: string | null;
  twitter: string | null;
  linkedin: string | null;
  google_scholar: string | null;
  research_gate: string | null;
  portfolio: string | null;
  password: string | null;
  profile_image: File | null;
  profile_img_preview: string | null;
  signature: File | null;
  sign_preview: string | null;
  current_profile_image: string | null; // Add this
  current_signature: string | null; // Add this
}

const form = useForm<FormData>({
  advisor_id: props.advisor.id,
  advisor_name: props.advisor.advisor_name,
  email: props.advisor.email,
  department: props.advisor.department,
  designation: props.advisor.designation,
  club_role: props.advisor.club_role,
  facebook: props.advisor.facebook || null,
  twitter: props.advisor.twitter || null,
  linkedin: props.advisor.linkedin || null,
  google_scholar: props.advisor.google_scholar || null,
  research_gate: props.advisor.research_gate || null,
  portfolio: props.advisor.portfolio || null,
  password: null,
  profile_image: null,
  profile_img_preview: props.advisor.profile_image 
    ? `/storage/${props.advisor.profile_image}`
    : null,
  signature: null,
  sign_preview: props.advisor.signature 
    ? `/storage/${props.advisor.signature}`
    : null,
  current_profile_image: props.advisor.profile_image || null, // Add this
  current_signature: props.advisor.signature || null, // Add this
});

const showPass = ref(false);

const togglePassword = () => {
  showPass.value = !showPass.value;
};

const showProfile = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.profile_image = file;
    form.profile_img_preview = URL.createObjectURL(file);
  }
};

const showSign = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.signature = file;
    form.sign_preview = URL.createObjectURL(file);
  }
};



const submitForm = () => {
  form.post("/advisors-and-moderators/update", {
    // Changed endpoint
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String("Advisor Updated successfully!"),
      });
    },
  });
};

// Reset file previews if they exist
onMounted(() => {
  if (props.advisor.profile_image) {
    form.profile_img_preview = `/storage/${props.advisor.profile_image}`;
  }
  if (props.advisor.signature) {
    form.sign_preview = `/storage/${props.advisor.signature}`;
  }
});
</script>

<template>
  <Head :title="'Edit Advisor - ' + advisor.advisor_name" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <div class="mb-6">
        <Link
          href="/advisors-and-moderators"
          class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors font-medium mb-4"
        >
          <ArrowLeft class="w-4 h-4" />
          Back to List
        </Link>
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
          <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 capitalize">
            Edit Advisor: {{ advisor.advisor_name }}
          </h1>
        </div>
      </div>

      <form
        @submit.prevent="submitForm"
        class="bg-teal-50 p-4 lg:p-6 rounded-xl shadow-lg border border-teal-100 grid lg:grid-cols-2 gap-6"
      >
        <h1
          class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700 flex items-center justify-center gap-2"
        >
          <Pencil class="w-6 h-6" />
          Edit Advisor Information
        </h1>

        <Input
          placeholder="Professor Name"
          v-model="form.advisor_name"
          :message="form.errors.advisor_name"
          type="text"
          required
        />

        <Input
          placeholder="Email"
          v-model="form.email"
          :message="form.errors.email"
          type="email"
          required
        />

        <Input
          placeholder="Department"
          v-model="form.department"
          :message="form.errors.department"
          type="text"
          required
        />

        <Input
          placeholder="Designation"
          v-model="form.designation"
          :message="form.errors.designation"
          type="text"
          required
        />

        <div class="w-full">
          <select
            v-model="form.club_role"
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50"
            :class="
              form.errors.club_role
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
            "
            required
          >
            <option disabled value="">Select Club Role</option>
            <option value="Advisor">Advisor</option>
            <option value="Moderator">Moderator</option>
          </select>
          <small
            v-if="form.errors.club_role"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.club_role }}
          </small>
        </div>

        <!-- Social Media Links Section -->
        <div class="col-span-2 mt-2">
          <h3 class="text-lg font-bold text-teal-700 mb-4 border-b border-teal-200 pb-2">
            Social Media & Professional Links
          </h3>
        </div>

        <Input
          placeholder="Facebook Link"
          v-model="form.facebook"
          :message="form.errors.facebook"
          type="url"
        />

        <Input
          placeholder="Twitter Link"
          v-model="form.twitter"
          :message="form.errors.twitter"
          type="url"
        />

        <Input
          placeholder="Linkedin Link"
          v-model="form.linkedin"
          :message="form.errors.linkedin"
          type="url"
        />

        <Input
          placeholder="Google Scholar Link"
          v-model="form.google_scholar"
          :message="form.errors.google_scholar"
          type="url"
        />

        <Input
          placeholder="Researchgate Link"
          v-model="form.research_gate"
          :message="form.errors.research_gate"
          type="url"
        />

        <Input
          placeholder="Portfolio Website Link"
          v-model="form.portfolio"
          :message="form.errors.portfolio"
          type="url"
        />

        <!-- Password Update Section -->
        <div class="col-span-2 mt-2">
          <h3 class="text-lg font-bold text-teal-700 mb-4 border-b border-teal-200 pb-2">
            Update Password (Optional)
          </h3>
        </div>

        <div class="relative col-span-2 lg:col-span-1">
          <Input
            placeholder="New Password (Leave empty to keep current)"
            v-model="form.password"
            :message="form.errors.password"
            :type="showPass ? 'text' : 'password'"
          />

          <button type="button" @click="togglePassword" class="absolute right-3 top-3">
            <Eye class="w-6 text-teal-400 cursor-pointer" v-if="!showPass" />
            <EyeOffIcon class="w-6 text-teal-400 cursor-pointer" v-else />
          </button>
          <small class="text-gray-500 text-sm mt-2 block">
            Leave blank to keep the current password
          </small>
        </div>

        <!-- File Uploads Section -->
        <div class="col-span-2 mt-2">
          <h3 class="text-lg font-bold text-teal-700 mb-4 border-b border-teal-200 pb-2">
            Update Images (Optional)
          </h3>
        </div>

        <div class="relative">
          <div class="mb-4">
            <label class="block text-sm font-medium text-teal-700 mb-2">
              Current Profile Picture:
            </label>
            <div v-if="form.profile_img_preview" class="mb-4">
              <img
                :src="form.profile_img_preview"
                alt="Current Profile"
                class="w-48 h-48 rounded-xl object-cover border-4 border-teal-200 shadow-md"
              />
            </div>
            <div v-else class="w-48 h-48 rounded-xl bg-gradient-to-r from-teal-100 to-teal-50 border-4 border-teal-200 flex items-center justify-center mb-4">
              <User class="w-24 h-24 text-teal-300" />
            </div>
          </div>
          
          <label
            for="profile_image"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <span class="text-center font-medium">Update Profile Picture</span>
            <span class="text-sm text-teal-500 mt-1">Click to browse new image</span>
          </label>
          <input
            type="file"
            id="profile_image"
            class="hidden"
            accept="image/*"
            @change="showProfile"
          />
          <small
            v-if="form.errors.profile_image"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.profile_image }}
          </small>
          <small class="text-gray-500 text-sm mt-2 block">
            Leave empty to keep the current profile picture
          </small>
        </div>

        <div class="relative">
          <div class="mb-4">
            <label class="block text-sm font-medium text-teal-700 mb-2">
              Current Signature:
            </label>
            <div v-if="form.sign_preview" class="mb-4">
              <img
                :src="form.sign_preview"
                alt="Current Signature"
                class="w-48 h-32 rounded-lg object-contain border-2 border-teal-300 bg-white p-2 shadow-sm"
              />
            </div>
            <div v-else class="w-48 h-32 rounded-lg bg-gradient-to-r from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center mb-4">
              <FileText class="w-16 h-16 text-teal-300" />
            </div>
          </div>
          
          <label
            for="signature"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <span class="text-center font-medium">Update Signature</span>
            <span class="text-sm text-teal-500 mt-1">Click to browse new signature</span>
          </label>
          <input
            type="file"
            id="signature"
            class="hidden"
            accept="image/*"
            @change="showSign"
          />
          <small
            v-if="form.errors.signature"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.signature }}
          </small>
          <small class="text-gray-500 text-sm mt-2 block">
            Leave empty to keep the current signature
          </small>
        </div>

        <!-- Hidden fields to preserve current file paths -->
        <input type="hidden" name="current_profile_image" v-model="form.current_profile_image" />
        <input type="hidden" name="current_signature" v-model="form.current_signature" />

        <!-- Submit Button -->
        <div class="col-span-2 mt-6">
          <div class="flex flex-col lg:flex-row gap-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 px-10 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-xl rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
            >
              <span v-if="form.processing">Updating...</span>
              <span v-else class="flex items-center gap-2">
                <Pencil class="w-5 h-5" />
                UPDATE ADVISOR
              </span>
            </button>
            
            <Link
              href="/advisors-and-moderators"
              class="flex-1 px-10 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold text-xl rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center min-w-[200px]"
            >
              <span class="flex items-center gap-2">
                <ArrowLeft class="w-5 h-5" />
                CANCEL
              </span>
            </Link>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>