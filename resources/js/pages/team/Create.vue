<script setup lang="ts">
import Input from "@/components/Input.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import RichTextEditor from "@/components/RichTextEditor.vue";
import Swal from "sweetalert2";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Teammates / Create",
    href: "/manage-team/create",
  },
];

interface FormData {
  teammate_name: string | null;
  department: string | null;
  designation: string | null;
  membership_period: string | null;
  small_desc: string | null;
  details: string;
  facebook_link: string | null;
  linkedin_link: string | null;
  teammate_image: File | null; // Changed from profile_image
  teammate_image_preview: string | null; // For preview only
}

const form = useForm<FormData>({
  teammate_name: null,
  department: null,
  designation: null,
  membership_period: null,
  small_desc: null,
  details: "",
  facebook_link: null,
  linkedin_link: null,
  teammate_image: null, // Changed from profile_image
  teammate_image_preview: null, // For preview only
});

const showProfile = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.teammate_image = file; // Changed from profile_image
    form.teammate_image_preview = URL.createObjectURL(file); // For preview
  }
};

const handleSubmit = () => {
  // Create FormData to properly handle file upload
  const formData = new FormData();
  
  // Append all form data
  if (form.teammate_image) {
    formData.append('teammate_image', form.teammate_image);
  }
  formData.append('teammate_name', form.teammate_name || '');
  formData.append('department', form.department || '');
  formData.append('designation', form.designation || '');
  formData.append('membership_period', form.membership_period || '');
  formData.append('small_desc', form.small_desc || '');
  formData.append('details', form.details || '');
  formData.append('facebook_link', form.facebook_link || '');
  formData.append('linkedin_link', form.linkedin_link || '');

  // Use Inertia's post method with FormData
  form.transform((data) => ({
    ...data,
    teammate_image: form.teammate_image,
  })).post('/manage-team/store', {
    preserveScroll: true,
    forceFormData: true, // Important for file uploads
    onSuccess: () => {
      form.reset();
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: String("Teammate created successfully!"),
      });
    },
    onError: (errors) => {
      console.error('Form errors:', errors);
    },
  });
};
</script>

<template>
  <Head title="Add A Teammate" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <form @submit.prevent="handleSubmit" class="bg-teal-50 p-4 lg:p-6 rounded-lg shadow grid lg:grid-cols-2 gap-3">
        <h1
          class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700"
        >
          Add A Teammate
        </h1>

        <div class="relative">
          <label
            for="teammate_image"
            class="block w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-100 flex flex-col items-center justify-center"
          >
            <img
              v-if="form.teammate_image_preview"
              :src="form.teammate_image_preview"
              alt="Profile Preview"
              class="w-[200px] rounded border-teal-500 bg-teal-500 mt-4 mb-2"
            />
            <span v-else class="text-center">Browse Profile Picture</span>
          </label>
          <input
            type="file"
            id="teammate_image"
            name="teammate_image"
            class="hidden"
            accept="image/*"
            @change="showProfile"
            required
          />
          <small
            v-if="form.errors.teammate_image"
            class="text-red-500 font-medium text-sm mt-2 block"
          >
            {{ form.errors.teammate_image }}
          </small>
        </div>

        <Input
          placeholder="Teammate Name"
          v-model="form.teammate_name"
          :message="form.errors.teammate_name"
          type="text"
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

        <Input
          placeholder="Membership Period. eg: 2024-25"
          v-model="form.membership_period"
          :message="form.errors.membership_period"
          type="text"
          required
        />

        <Input
          placeholder="Facebook Link"
          v-model="form.facebook_link"
          :message="form.errors.facebook_link"
          type="text"
        />

        <Input
          placeholder="Linkedin Link"
          v-model="form.linkedin_link"
          :message="form.errors.linkedin_link"
          type="text"
        />

        <div class="relative">
          <textarea
            v-model="form.small_desc"
            placeholder="Small Description..."
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50 h-[50px]"
            :class="
              form.errors.small_desc
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
            "
          ></textarea>
          <small
            v-if="form.errors.small_desc"
            class="text-red-500 font-medium text-sm mt-2 block"
            >{{ form.errors.small_desc }}</small
          >
        </div>

        <div class="relative col-span-2">
            <RichTextEditor
              v-model="form.details"
              :error="form.errors.details"
              label="Member Details"
              placeholder="Write Member Details here..."
            />

            <small
            v-if="form.errors.details"
            class="text-red-500 font-medium text-sm mt-2 block"
            >{{ form.errors.details }}</small
          >
          </div>

        <div class="relative bottom-0 col-span-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full px-10 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-xl rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            <span v-if="form.processing">Processing...</span>
            <span v-else>SUBMIT</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>