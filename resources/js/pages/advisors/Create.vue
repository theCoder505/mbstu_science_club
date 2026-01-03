<script setup lang="ts">
import Input from "@/components/Input.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import { Eye, EyeOffIcon } from "lucide-vue-next";
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Advisor & Moderators / Create New",
    href: "/advisors-and-moderators/create",
  },
];

interface FormData {
  advisor_name: string | null;
  email: string | null;
  department: string | null;
  designation: string | null;
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
}

const form = useForm<FormData>({
  advisor_name: null,
  email: null,
  department: null,
  designation: null,
  club_role: "Advisor",
  facebook: null,
  twitter: null,
  linkedin: null,
  google_scholar: null,
  research_gate: null,
  portfolio: null,
  password: null,
  profile_image: null,
  profile_img_preview: null,
  signature: null,
  sign_preview: null,
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
  form.post("/advisors-and-moderators", {
    // Changed endpoint
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String("Advisor created successfully!"),
      });
    },
  });
};
</script>

<template>
  <Head title="Create New Advisor Or Moderator" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <Form
        @submit.prevent="submitForm"
        class="bg-teal-50 p-4 lg:p-6 rounded-lg shadow grid lg:grid-cols-2 gap-3"
      >
        <h1
          class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700"
        >
          Create New Advisor Or Moderator
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

        <Input
          placeholder="Facebook Link"
          v-model="form.facebook"
          :message="form.errors.facebook"
          type="text"
        />

        <Input
          placeholder="Twitter Link"
          v-model="form.twitter"
          :message="form.errors.twitter"
          type="text"
        />

        <Input
          placeholder="Linkedin Link"
          v-model="form.linkedin"
          :message="form.errors.linkedin"
          type="text"
        />

        <Input
          placeholder="Google Scholar Link"
          v-model="form.google_scholar"
          :message="form.errors.google_scholar"
          type="text"
        />

        <Input
          placeholder="Researchgate Link"
          v-model="form.research_gate"
          :message="form.errors.research_gate"
          type="text"
        />

        <Input
          placeholder="Portfolio Website Link"
          v-model="form.portfolio"
          :message="form.errors.portfolio"
          type="text"
        />

        <div class="relative">
          <Input
            placeholder="Password"
            v-model="form.password"
            :message="form.errors.password"
            :type="showPass ? 'text' : 'password'"
            required
          />

          <button type="button" @click="togglePassword" class="absolute right-3 top-3">
            <Eye class="w-6 text-teal-400 cursor-pointer" v-if="!showPass" />
            <EyeOffIcon class="w-6 text-teal-400 cursor-pointer" v-else />
          </button>
        </div>

        <div class="relative">
          <label
            for="profile_image"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <img
              v-if="form.profile_img_preview"
              :src="form.profile_img_preview || ''"
              alt="Profile Preview"
              class="w-[200px] rounded border-teal-500 bg-teal-500 mt-4 mb-2"
            />
            <span class="text-center">Browse Professor Picture</span>
          </label>
          <input
            type="file"
            id="profile_image"
            class="hidden"
            accept="image/*"
            @change="showProfile"
            required
          />
          <small
            v-if="form.errors.profile_image"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.profile_image }}
          </small>
        </div>

        <div class="relative">
          <label
            for="signature"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <img
              :src="form.sign_preview || ''"
              alt="Signature Preview"
              class="w-[200px] rounded border-teal-500 bg-teal-500 mt-4 mb-2"
              v-if="form.sign_preview"
            />
            <span class="text-center">Browse Professor Signature</span>
          </label>
          <input
            type="file"
            id="signature"
            class="hidden"
            accept="image/*"
            @change="showSign"
            required
          />
          <small
            v-if="form.errors.signature"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.signature }}
          </small>
        </div>

        <div class="relative bottom-0">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full px-10 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-xl rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            <span v-if="form.processing">Processing...</span>
            <span v-else>SUBMIT</span>
          </button>
        </div>
      </Form>
    </div>
  </AppLayout>
</template>
