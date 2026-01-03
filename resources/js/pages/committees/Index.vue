<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import {
  Plus,
  Pencil,
  Trash,
  Users,
  Calendar,
  Image as ImageIcon,
  Eye,
} from "lucide-vue-next";
import { ref, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Committees",
    href: "/our-committee",
  },
];

defineProps({
  committees: {
    type: Array,
    default: () => [],
  },
});

// Modal for image gallery
const showImageModal = ref(false);
const selectedCommittee = ref<any>(null);
const currentImageIndex = ref(0);

const openImageGallery = (committee: any, index: number = 0) => {
  selectedCommittee.value = committee;
  currentImageIndex.value = index;
  showImageModal.value = true;
};

const nextImage = () => {
  if (selectedCommittee.value && selectedCommittee.value.committee_images) {
    currentImageIndex.value =
      (currentImageIndex.value + 1) % selectedCommittee.value.committee_images.length;
  }
};

const prevImage = () => {
  if (selectedCommittee.value && selectedCommittee.value.committee_images) {
    currentImageIndex.value =
      currentImageIndex.value === 0
        ? selectedCommittee.value.committee_images.length - 1
        : currentImageIndex.value - 1;
  }
};

const getDate = (date: any) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-uk", {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  });
};

const askToDelete = (id: any) => {
  if (
    confirm(
      "Are you sure you want to delete this committee? This will delete all associated images."
    )
  ) {
    router.post(
      "/our-committee/delete",
      { id },
      {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Committee removed successfully!",
          });
        },
      }
    );
  }
};

const search = ref("");

watch(
  search,
  debounce(
    (query) => router.get("/our-committee", { search: query }, { preserveState: true }),
    300
  )
);
</script>

<template>
  <Head title="All Committees" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-6">
      <div>
        <h1 class="text-center text-2xl my-6 font-bold capitalize">Our Committees</h1>
      </div>

      <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
        <Link
          href="/our-committee/create"
          class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl text-white px-4 py-2.5 flex items-center gap-2 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
          <Plus class="w-5 h-5" />
          Create New Committee
        </Link>

        <div class="flex w-full lg:w-auto">
          <input
            type="search"
            placeholder="Search committees..."
            v-model="search"
            class="border px-4 py-2.5 rounded-xl border-teal-600 outline-0 w-full lg:w-[350px] focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="committee in (committees as any[])"
          :key="committee.id"
          class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow duration-300"
        >
          <!-- Images Gallery Preview -->
          <div class="relative h-48 overflow-hidden bg-gray-100">
            <!-- Main Image -->
            <img
              v-if="committee.committee_images && committee.committee_images.length > 0"
              :src="'/storage/' + committee.committee_images[0]"
              :alt="committee.committee_name"
              class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
              @click="openImageGallery(committee, 0)"
            />
            <!-- Placeholder if no images -->
            <div
              v-else
              class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teal-50 to-teal-100"
            >
              <div class="text-center">
                <ImageIcon class="w-12 h-12 text-teal-400 mx-auto mb-2" />
                <span class="text-teal-600 text-sm font-medium">No Images</span>
              </div>
            </div>

            <!-- Image Count Badge -->
            <div
              v-if="committee.committee_images && committee.committee_images.length > 1"
              class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1 cursor-pointer hover:bg-opacity-90"
              @click="openImageGallery(committee)"
            >
              <Eye class="w-3 h-3" />
              <span>{{ committee.committee_images.length }} images</span>
            </div>

            <!-- Period Badge -->
            <div
              class="absolute top-2 left-2 bg-teal-600 text-white text-xs px-2 py-1 rounded-md"
            >
              {{ committee.committee_period }}
            </div>
          </div>

          <!-- Committee Details -->
          <div class="p-4">
            <h3 class="text-lg font-bold text-gray-800 mb-2 capitalize truncate">
              {{ committee.committee_name }}
            </h3>

            <div class="flex items-center text-gray-600 text-sm mb-3">
              <Calendar class="w-4 h-4 mr-2" />
              <span>Created: {{ getDate(committee.created_at) }}</span>
            </div>

            <div class="flex items-center text-gray-600 text-sm mb-4">
              <ImageIcon class="w-4 h-4 mr-2" />
              <span
                >{{
                  committee.committee_images ? committee.committee_images.length : 0
                }}
                images</span
              >
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
              <Link
                :href="`/our-committee/${committee.id}`"
                class="flex items-center gap-2 px-3 py-1.5 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors"
                title="Edit"
              >
                <Pencil class="w-4 h-4" />
                <span class="text-sm font-medium">Edit</span>
              </Link>

              <button
                @click="askToDelete(committee.id)"
                class="flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                title="Delete"
              >
                <Trash class="w-4 h-4" />
                <span class="text-sm font-medium">Delete</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="committees.length === 0" class="py-12 text-center">
        <div
          class="mx-auto w-24 h-24 bg-teal-50 rounded-full flex items-center justify-center mb-4"
        >
          <Users class="w-12 h-12 text-teal-400" />
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No committees found</h3>
        <p class="text-gray-500 mb-6">Get started by creating a new committee.</p>
        <Link
          href="/our-committee/create"
          class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl text-white px-6 py-2.5 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
          <Plus class="w-5 h-5" />
          Create New Committee
        </Link>
      </div>
    </div>

    <!-- Image Gallery Modal -->
    <div
      v-if="showImageModal && selectedCommittee"
      class="fixed inset-0 bg-[#00000099] flex items-center justify-center z-50 p-4"
      @click.self="showImageModal = false"
    >
      <div class="relative max-w-4xl w-full max-h-[90vh]">
        <!-- Close Button -->
        <button
          @click="showImageModal = false"
          class="absolute -top-10 right-0 text-white text-2xl hover:text-teal-300 z-10 cursor-pointer"
        >
          ×
        </button>

        <!-- Main Image -->
        <div class="relative">
          <img
            :src="'/storage/' + selectedCommittee.committee_images[currentImageIndex]"
            :alt="`${selectedCommittee.committee_name} - Image ${currentImageIndex + 1}`"
            class="w-full h-auto max-h-[70vh] object-contain rounded-lg"
          />

          <!-- Navigation Arrows -->
          <button
            v-if="selectedCommittee.committee_images.length > 1"
            @click="prevImage"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-colors cursor-pointer"
          >
            ←
          </button>
          <button
            v-if="selectedCommittee.committee_images.length > 1"
            @click="nextImage"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-colors cursor-pointer"
          >
            →
          </button>
        </div>

        <!-- Image Info -->
        <div class="mt-4 text-white text-center">
          <h3 class="text-xl font-bold mb-1">{{ selectedCommittee.committee_name }}</h3>
          <p class="text-sm opacity-80">
            Image {{ currentImageIndex + 1 }} of
            {{ selectedCommittee.committee_images.length }}
          </p>
        </div>

        <!-- Thumbnails -->
        <div
          v-if="selectedCommittee.committee_images.length > 1"
          class="mt-4 flex justify-center gap-2 overflow-x-auto py-2"
        >
          <button
            v-for="(image, index) in (selectedCommittee?.committee_images as any[])"
            :key="index"
            @click="currentImageIndex = index"
            class="flex-shrink-0 w-16 h-16 rounded overflow-hidden"
            :class="{ 'ring-2 ring-teal-400': index === currentImageIndex }"
          >
            <img
              :src="'/storage/' + image"
              :alt="`Thumbnail ${index + 1}`"
              class="w-full h-full object-cover cursor-pointer"
            />
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
