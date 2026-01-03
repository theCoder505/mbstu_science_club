<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { Form, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, onMounted } from "vue";

interface CommitteeData {
  id: string;
  committee_name: string;
  committee_period: string;
  committee_images?: string[]; // Array of storage paths
  images?: string[]; // Array of full URLs
}

const props = defineProps<{
  committee: CommitteeData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Committee",
    href: "/our-committee",
  },
  {
    title: `Edit ${props.committee.committee_name}`,
    href: `/our-committee/${props.committee.id}`,
  },
];

// Initialize existing images from committee data
const initialImages = props.committee.committee_images || [];

// Form data
const committeeId = ref(props.committee.id);
const committeeName = ref(props.committee.committee_name);
const committeePeriod = ref(props.committee.committee_period);
const existingImages = ref<string[]>([...initialImages]); // Store the storage paths
const removedImages = ref<string[]>([]);
const isProcessing = ref(false);

// For displaying images in UI
const existingImagePreviews = ref<string[]>([]);
const newImagePreviews = ref<string[]>([]);
const newImageFiles = ref<File[]>([]);

// Error handling
const errors = ref<Record<string, string>>({});

// Load existing images as previews
onMounted(() => {
  // If we have committee.images (full URLs), use those
  if (props.committee.images && props.committee.images.length > 0) {
    existingImagePreviews.value = props.committee.images;
  }
  // Otherwise, create URLs from storage paths
  else if (existingImages.value.length > 0) {
    existingImagePreviews.value = existingImages.value.map(path => 
      `${window.location.origin}/storage/${path}`
    );
  }
});

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const files = Array.from(target.files);
    
    // Store files for FormData submission
    newImageFiles.value.push(...files);
    
    // Create previews for new images
    files.forEach(file => {
      const reader = new FileReader();
      reader.onload = (e) => {
        if (e.target?.result) {
          newImagePreviews.value.push(e.target.result as string);
        }
      };
      reader.readAsDataURL(file);
    });
    
    // Reset the input to allow uploading same files again
    target.value = '';
  }
};

const removeExistingImage = (index: number) => {
  // Get the storage path of the image to remove
  const removedImagePath = existingImages.value[index];
  
  // Add to removed images array
  if (removedImagePath) {
    removedImages.value.push(removedImagePath);
  }
  
  // Remove from existing images array
  existingImages.value.splice(index, 1);
  existingImagePreviews.value.splice(index, 1);
};

const removeNewImage = (index: number) => {
  // Remove from new images arrays
  newImageFiles.value.splice(index, 1);
  newImagePreviews.value.splice(index, 1);
};

const submitForm = () => {
  // Create FormData object
  const formData = new FormData();
  
  // Append basic fields
  formData.append('id', committeeId.value);
  formData.append('committee_name', committeeName.value);
  formData.append('committee_period', committeePeriod.value);
  
  // Append existing images (storage paths that user wants to keep)
  existingImages.value.forEach((imagePath, index) => {
    formData.append(`existing_images[${index}]`, imagePath);
  });
  
  // Append removed images
  removedImages.value.forEach((imagePath, index) => {
    formData.append(`removed_images[${index}]`, imagePath);
  });
  
  // Append new image files
  newImageFiles.value.forEach((file, index) => {
    formData.append(`committee_images[${index}]`, file);
  });
  
  // Submit the form using Inertia router
  isProcessing.value = true;
  errors.value = {};
  
  router.post('/our-committee/update', formData, {
    preserveScroll: true,
    forceFormData: true, // Important: Force Inertia to send as FormData
    onSuccess: () => {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Committee updated successfully!",
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        
      });
    },
    onError: (responseErrors) => {
      console.error('Update failed:', responseErrors);
      errors.value = responseErrors;
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Failed to update committee. Please check the form and try again.",
      });
    },
    onFinish: () => {
      isProcessing.value = false;
    }
  });
};
</script>

<template>
  <Head :title="`Edit ${committee.committee_name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-12">
      <Form @submit.prevent="submitForm" class="bg-teal-50 p-4 lg:p-6 rounded-lg shadow grid lg:grid-cols-2 gap-6">
        <h1 class="text-center text-2xl capitalize col-span-2 mb-2 font-bold text-teal-700">
          Edit Committee: {{ committee.committee_name }}
        </h1>

        <!-- Committee Name -->
        <div class="col-span-2 lg:col-span-1">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Committee Name *
          </label>
          <input
            v-model="committeeName"
            type="text"
            required
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-gray-700 placeholder-gray-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400"
          />
          <small v-if="errors.committee_name" class="text-red-500 font-medium text-sm mt-2 block">
            {{ errors.committee_name }}
          </small>
        </div>

        <!-- Committee Period -->
        <div class="col-span-2 lg:col-span-1">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Committee Period *
          </label>
          <input
            v-model="committeePeriod"
            type="text"
            required
            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-gray-700 placeholder-gray-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400"
          />
          <small v-if="errors.committee_period" class="text-red-500 font-medium text-sm mt-2 block">
            {{ errors.committee_period }}
          </small>
        </div>

        <!-- Existing Images -->
        <div v-if="existingImagePreviews.length > 0" class="col-span-2">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Existing Images (Click to remove)
          </label>
          <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
            <div
              v-for="(preview, index) in existingImagePreviews"
              :key="'existing-' + index"
              class="relative group cursor-pointer"
              @click="removeExistingImage(index)"
              :title="`Click to remove this image`"
            >
              <img
                :src="preview"
                :alt="`Image ${index + 1}`"
                class="w-full h-32 object-cover rounded-lg border-2 border-teal-200 group-hover:border-red-400 transition-all"
              />
              <div class="absolute inset-0 hover:bg-[#00000070] group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                <span class="text-white font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                  Remove
                </span>
              </div>
              <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                ×
              </div>
            </div>
          </div>
        </div>

        <!-- No Existing Images Message -->
        <div v-else class="col-span-2">
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-yellow-700">
              No existing images for this committee. You can add images below.
            </p>
          </div>
        </div>

        <!-- New Image Upload -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-teal-700 mb-2">
            Add More Images
          </label>
          
          <!-- New Image Previews -->
          <div v-if="newImagePreviews.length > 0" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
            <div
              v-for="(preview, index) in newImagePreviews"
              :key="'new-' + index"
              class="relative group"
            >
              <img
                :src="preview"
                :alt="`New Image ${index + 1}`"
                class="w-full h-32 object-cover rounded-lg border-2 border-green-200"
              />
              <button
                type="button"
                @click="removeNewImage(index)"
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
              class="block w-full px-4 py-6 border-2 border-dashed rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-white border-teal-300 focus:ring-teal-500 focus:border-teal-400 cursor-pointer hover:bg-teal-50 flex flex-col items-center justify-center transition-colors"
            >
              <svg class="w-10 h-10 text-teal-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span class="text-center font-medium">Add More Images</span>
              <span class="text-sm text-gray-500 mt-1">Upload additional images (JPEG, PNG, GIF, max 20MB each)</span>
            </label>
            <input
              type="file"
              id="committee-images"
              class="hidden"
              accept="image/jpeg,image/png,image/gif,image/jpg"
              @change="handleImageUpload"
              multiple
            />
          </div>
          <small v-if="errors.committee_images" class="text-red-500 font-medium text-sm mt-2 block">
            {{ errors.committee_images }}
          </small>
        </div>

        <!-- Action Buttons -->
        <div class="col-span-2 flex flex-col sm:flex-row gap-4">
          <button
            type="submit"
            :disabled="isProcessing"
            class="w-full sm:w-auto px-10 py-3 bg-teal-600 text-white font-bold text-xl rounded-xl hover:bg-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center"
          >
            <svg v-if="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ isProcessing ? 'Updating...' : 'UPDATE COMMITTEE' }}</span>
          </button>
          
          <Link
            href="/our-committee"
            class="w-full sm:w-auto px-10 py-3 bg-gray-200 text-gray-700 font-bold text-xl rounded-xl hover:bg-gray-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center text-center"
          >
            CANCEL
          </Link>
        </div>
      </Form>
    </div>
  </AppLayout>
</template>