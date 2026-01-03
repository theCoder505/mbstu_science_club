<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref } from "vue";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Committee / Create New",
    href: "/our-committee",
  },
  {
    title: "Create Committee",
    href: "/our-committee/create",
  },
];

interface FormData {
  committee_name: string | null;
  committee_period: string | null;
  committee_images: File[] | null;
  processing: boolean;
}

const form = useForm<FormData>({
  committee_name: null,
  committee_period: null,
  committee_images: null,
  processing: false,
});

const imagePreviews = ref<string[]>([]);

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files) {
    const files = Array.from(target.files);
    
    // Add to form data
    if (!form.committee_images) {
      form.committee_images = files;
    } else {
      form.committee_images = [...form.committee_images, ...files];
    }
    
    // Create previews
    files.forEach(file => {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviews.value.push(e.target?.result as string);
      };
      reader.readAsDataURL(file);
    });
  }
};

const removeImage = (index: number) => {
  // Remove from previews
  imagePreviews.value.splice(index, 1);
  
  // Remove from form data
  if (form.committee_images) {
    const files = Array.from(form.committee_images);
    files.splice(index, 1);
    form.committee_images = files.length > 0 ? files : null;
  }
};

const submitForm = () => {
  const formData = new FormData();
  
  if (form.committee_name) formData.append('committee_name', form.committee_name);
  if (form.committee_period) formData.append('committee_period', form.committee_period);
  
  // Append all images
  if (form.committee_images) {
    Array.from(form.committee_images).forEach((file, index) => {
      formData.append(`committee_images[${index}]`, file);
    });
  }
  
  form.post("/our-committee/store", {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      imagePreviews.value = [];
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Committee created successfully!",
      });
    },
  });
};
</script>

<template>
  <Head title="Create New Committee" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <form @submit.prevent="submitForm" class="bg-teal-50 p-4 lg:p-6 rounded-lg shadow grid lg:grid-cols-2 gap-6">
        <h1 class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700">
          Create New Committee
        </h1>

        <!-- Committee Name -->
        <div class="col-span-2 lg:col-span-1">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Committee Name *
          </label>
          <input
            v-model="form.committee_name"
            type="text"
            required
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-gray-700 placeholder-gray-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400"
            placeholder="Enter committee name"
          />
          <small v-if="form.errors.committee_name" class="text-red-500 font-medium text-sm mt-2 block">
            {{ form.errors.committee_name }}
          </small>
        </div>

        <!-- Committee Period -->
        <div class="col-span-2 lg:col-span-1">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Committee Period *
          </label>
          <input
            v-model="form.committee_period"
            type="text"
            required
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-gray-700 placeholder-gray-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400"
            placeholder="e.g., 2023-2024"
          />
          <small v-if="form.errors.committee_period" class="text-red-500 font-medium text-sm mt-2 block">
            {{ form.errors.committee_period }}
          </small>
        </div>

        <!-- Image Upload -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Committee Images *
          </label>
          
          <!-- Image Previews -->
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
            <div
              v-for="(preview, index) in imagePreviews"
              :key="index"
              class="relative group"
            >
              <img
                :src="preview"
                :alt="`Preview ${index + 1}`"
                class="w-full h-32 object-cover rounded-lg border-2 border-teal-200"
              />
              <button
                type="button"
                @click="removeImage(index)"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
              >
                ×
              </button>
            </div>
          </div>

          <!-- Upload Area -->
          <div class="relative">
            <label
              for="committee-images"
              class="block w-full px-4 py-8 border-2 border-dashed rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-50 flex flex-col items-center justify-center transition-colors"
            >
              <svg class="w-12 h-12 text-teal-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-center font-medium">Click to upload images</span>
              <span class="text-sm text-gray-500 mt-1">Upload multiple images (JPG, PNG, GIF)</span>
            </label>
            <input
              type="file"
              id="committee-images"
              class="hidden"
              accept="image/*"
              @change="handleImageUpload"
              multiple
              required
            />
          </div>
          <small v-if="form.errors.committee_images" class="text-red-500 font-medium text-sm mt-2 block">
            {{ form.errors.committee_images }}
          </small>
          <small class="text-gray-500 text-sm mt-2 block">
            Upload at least one image. You can select multiple images at once.
          </small>
        </div>

        <!-- Submit Button -->
        <div class="col-span-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full px-10 py-3 bg-teal-600 text-white font-bold text-xl rounded-xl hover:bg-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
          >
            <span v-if="form.processing">Processing...</span>
            <span v-else>CREATE COMMITTEE</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>