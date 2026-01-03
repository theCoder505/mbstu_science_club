<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { ref, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {
  Eye,
  EyeOff,
  Trash2,
  Edit,
  Plus,
  Save,
  X,
  Image as ImageIcon,
} from "lucide-vue-next";

interface GalleryItem {
  id: number;
  image_path: string;
  status: string;
  position: number;
}

interface Props {
  gallery: GalleryItem[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Gallery",
    href: "/gallery",
  },
];

// State
const galleryItems = ref<GalleryItem[]>([...props.gallery]);
const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const draggedItem = ref<number | null>(null);
const hasChanges = ref(false);
const selectedItem = ref<GalleryItem | null>(null);

// Watch for prop changes and update local state
watch(
  () => props.gallery,
  (newGallery) => {
    galleryItems.value = [...newGallery];
    hasChanges.value = false;
  },
  { deep: true }
);

// Forms
const addForm = useForm({
  images: [] as File[],
});

const editForm = useForm({
  id: 0,
  image_path: "",
  new_image: null as File | null,
  status: "show",
  position: 0,
});

// Computed
const imagePreviews = ref<string[]>([]);
const editImagePreview = ref<string | null>(null);

// Drag and Drop Handlers
const handleDragStart = (index: number) => {
  draggedItem.value = index;
};

const handleDragOver = (e: DragEvent) => {
  e.preventDefault();
};

const handleDrop = (targetIndex: number) => {
  if (draggedItem.value === null) return;

  const items = [...galleryItems.value];
  const draggedElement = items[draggedItem.value];

  // Remove dragged item
  items.splice(draggedItem.value, 1);
  // Insert at new position
  items.splice(targetIndex, 0, draggedElement);

  // Update positions
  items.forEach((item, index) => {
    item.position = index + 1;
  });

  galleryItems.value = items;
  draggedItem.value = null;
  hasChanges.value = true;
};

const handleDragEnd = () => {
  draggedItem.value = null;
};

// Multiple Image Preview
const handleImageSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const files = target.files;

  if (files && files.length > 0) {
    addForm.images = Array.from(files);
    imagePreviews.value = [];

    Array.from(files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviews.value.push(e.target?.result as string);
      };
      reader.readAsDataURL(file);
    });
  }
};

// Handle new image selection in edit modal
const handleEditImageSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    editForm.new_image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      editImagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

// Remove new image selection in edit modal
const removeEditImagePreview = () => {
  editForm.new_image = null;
  editImagePreview.value = null;
  // Reset the file input
  const fileInput = document.getElementById('edit-image-input') as HTMLInputElement;
  if (fileInput) {
    fileInput.value = '';
  }
};

// Remove image from preview
const removeImagePreview = (index: number) => {
  addForm.images.splice(index, 1);
  imagePreviews.value.splice(index, 1);
};

// CRUD Operations
const openAddModal = () => {
  isAddModalOpen.value = true;
  addForm.reset();
  imagePreviews.value = [];
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
  addForm.reset();
  imagePreviews.value = [];
};

const submitAddForm = () => {
  addForm.post("/gallery/store", {
    preserveScroll: true,
    onSuccess: () => {
      closeAddModal();
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Added Successfully!",
        timer: 1500,
        showConfirmButton: false,
      });
    },
    onError: () => {
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Failed to add images. Please try again.",
      });
    },
  });
};

const openEditModal = (item: GalleryItem) => {
  selectedItem.value = item;
  editForm.id = item.id;
  editForm.image_path = item.image_path;
  editForm.status = item.status;
  editForm.position = item.position;
  editForm.new_image = null;
  editImagePreview.value = null;
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
  selectedItem.value = null;
  editForm.reset();
  editImagePreview.value = null;
};

const submitEditForm = () => {
  editForm.post("/gallery/update", {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      closeEditModal();
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Image updated successfully!",
        timer: 1500,
        showConfirmButton: false,
      });
    },
    onError: () => {
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Failed to update image. Please try again.",
      });
    },
  });
};

const deleteImage = (id: number) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/gallery/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "Image has been deleted.",
            timer: 1500,
            showConfirmButton: false,
          });
        },
      });
    }
  });
};

const toggleStatus = (item: GalleryItem) => {
  const newStatus = item.status === "show" ? "hide" : "show";

  router.put(
    "/gallery/update",
    {
      id: item.id,
      status: newStatus,
      image_path: item.image_path,
      position: item.position,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: `Image ${newStatus === "show" ? "shown" : "hidden"} successfully!`,
          timer: 1500,
          showConfirmButton: false,
        });
      },
    }
  );
};

const savePositions = () => {
  const positions = galleryItems.value.map((item) => ({
    id: item.id,
    position: item.position,
  }));

  router.post(
    "/gallery/update-positions",
    { positions },
    {
      preserveScroll: true,
      onSuccess: () => {
        hasChanges.value = false;
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Positions updated successfully!",
          timer: 1500,
          showConfirmButton: false,
        });
      },
      onError: () => {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to update positions. Please try again.",
        });
      },
    }
  );
};

const getImageUrl = (path: string) => {
  return `/storage/${path}`;
};
</script>

<template>
  <Head title="Gallery" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold capitalize">Gallery Management</h1>
        <div class="flex gap-3">
          <button
            v-if="hasChanges"
            @click="savePositions"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            <Save :size="20" />
            Save Positions
          </button>
          <button
            @click="openAddModal"
            class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
          >
            <Plus :size="20" />
            Add Images
          </button>
        </div>
      </div>

      <!-- Gallery Grid -->
      <div
        v-if="galleryItems.length > 0"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4"
      >
        <div
          v-for="(item, index) in galleryItems"
          :key="item.id"
          :draggable="true"
          @dragstart="handleDragStart(index)"
          @dragover="handleDragOver"
          @drop="handleDrop(index)"
          @dragend="handleDragEnd"
          :class="[
            'relative group cursor-move rounded-lg overflow-hidden border-2 transition-all',
            draggedItem === index
              ? 'opacity-50 border-teal-500'
              : 'border-gray-200 hover:border-teal-400',
          ]"
        >
          <!-- Image -->
          <div class="aspect-square bg-gray-100">
            <img
              :src="getImageUrl(item.image_path)"
              :alt="`Gallery image ${item.position}`"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Overlay -->
          <div
            class="absolute inset-0 hover:bg-[#00000070] transition-all duration-200 flex items-center justify-center gap-2"
          >
            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
              <!-- Status Toggle -->
              <button
                @click="toggleStatus(item)"
                :class="[
                  'p-2 rounded-full transition-colors',
                  item.status === 'show'
                    ? 'bg-blue-600 hover:bg-blue-700'
                    : 'bg-gray-600 hover:bg-gray-700',
                ]"
                :title="item.status === 'show' ? 'Hide' : 'Show'"
              >
                <Eye v-if="item.status === 'show'" :size="20" class="text-white" />
                <EyeOff v-else :size="20" class="text-white" />
              </button>

              <!-- Edit -->
              <button
                @click="openEditModal(item)"
                class="p-2 bg-teal-600 hover:bg-teal-700 rounded-full transition-colors"
                title="Edit"
              >
                <Edit :size="20" class="text-white" />
              </button>

              <!-- Delete -->
              <button
                @click="deleteImage(item.id)"
                class="p-2 bg-red-600 hover:bg-red-700 rounded-full transition-colors"
                title="Delete"
              >
                <Trash2 :size="20" class="text-white" />
              </button>
            </div>
          </div>

          <!-- Status Badge -->
          <div class="absolute top-2 right-2">
            <span
              :class="[
                'px-2 py-1 text-xs font-semibold rounded-full',
                item.status === 'show'
                  ? 'bg-blue-500 text-white'
                  : 'bg-gray-500 text-white',
              ]"
            >
              {{ item.status === "show" ? "Visible" : "Hidden" }}
            </span>
          </div>

          <!-- Position Badge -->
          <div class="absolute top-2 left-2">
            <span class="px-2 py-1 text-xs font-semibold bg-teal-500 text-white rounded-full">
              #{{ item.position }}
            </span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-else
        class="text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300"
      >
        <ImageIcon :size="64" class="mx-auto text-gray-400 mb-4" />
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Images Yet</h3>
        <p class="text-gray-500 mb-4">Start building your gallery by adding images</p>
        <button
          @click="openAddModal"
          class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
        >
          <Plus :size="20" />
          Add Your First Image
        </button>
      </div>

      <!-- Add Modal -->
      <Teleport to="body">
        <div
          v-if="isAddModalOpen"
          class="fixed inset-0 z-50 flex items-center justify-center bg-[#00000070] p-4"
          @click.self="closeAddModal"
        >
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Add New Images</h2>
                <button
                  @click="closeAddModal"
                  class="text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <X :size="24" />
                </button>
              </div>

              <!-- Form -->
              <form @submit.prevent="submitAddForm" class="space-y-4">
                <!-- Image Upload -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Images <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="file"
                    @change="handleImageSelect"
                    accept="image/*"
                    multiple
                    required
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                  />
                  <p class="mt-1 text-sm text-gray-500">
                    You can select multiple images at once. All images will be set to "Show" by default.
                  </p>
                  <p v-if="addForm.errors.images" class="mt-1 text-sm text-red-600">
                    {{ addForm.errors.images }}
                  </p>

                  <!-- Image Previews -->
                  <div v-if="imagePreviews.length > 0" class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">
                      Selected Images ({{ imagePreviews.length }})
                    </p>
                    <div class="grid grid-cols-3 gap-4">
                      <div
                        v-for="(preview, index) in imagePreviews"
                        :key="index"
                        class="relative group"
                      >
                        <img
                          :src="preview"
                          :alt="`Preview ${index + 1}`"
                          class="w-full h-32 object-cover rounded-lg border-2 border-gray-200"
                        />
                        <button
                          type="button"
                          @click="removeImagePreview(index)"
                          class="absolute top-2 right-2 p-1 bg-red-600 hover:bg-red-700 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                          title="Remove"
                        >
                          <X :size="16" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Info -->
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                  <p class="text-sm text-teal-800">
                    <strong>Note:</strong> All uploaded images will be visible by default. You can hide or change the status of individual images later by editing them.
                  </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                  <button
                    type="button"
                    @click="closeAddModal"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="addForm.processing || imagePreviews.length === 0"
                    class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ addForm.processing ? "Uploading..." : `Upload ${imagePreviews.length} Image(s)` }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </Teleport>

      <!-- Edit Modal -->
      <Teleport to="body">
        <div
          v-if="isEditModalOpen"
          class="fixed inset-0 z-50 flex items-center justify-center bg-[#00000070] p-4"
          @click.self="closeEditModal"
        >
          <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
              <!-- Modal Header -->
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Edit Image</h2>
                <button
                  @click="closeEditModal"
                  class="text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <X :size="24" />
                </button>
              </div>

              <!-- Form -->
              <form @submit.prevent="submitEditForm" class="space-y-4">
                <!-- Current Image -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ editImagePreview ? 'New Image (Preview)' : 'Current Image' }}
                  </label>
                  <img
                    :src="editImagePreview || getImageUrl(editForm.image_path)"
                    :alt="editImagePreview ? 'New image preview' : 'Current image'"
                    class="w-full h-48 object-cover rounded-lg border-2 border-gray-200"
                  />
                </div>

                <!-- Replace Image -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Replace Image (Optional)
                  </label>
                  <div class="space-y-2">
                    <input
                      id="edit-image-input"
                      type="file"
                      @change="handleEditImageSelect"
                      accept="image/*"
                      class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                    />
                    <p class="text-xs text-gray-500">
                      Leave empty to keep the current image
                    </p>
                    <button
                      v-if="editImagePreview"
                      type="button"
                      @click="removeEditImagePreview"
                      class="flex items-center gap-1 text-sm text-red-600 hover:text-red-700"
                    >
                      <X :size="16" />
                      Remove new image
                    </button>
                  </div>
                  <p v-if="editForm.errors.new_image" class="mt-1 text-sm text-red-600">
                    {{ editForm.errors.new_image }}
                  </p>
                </div>

                <!-- Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="editForm.status"
                    required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                  >
                    <option value="show">Show</option>
                    <option value="hide">Hide</option>
                  </select>
                  <p v-if="editForm.errors.status" class="mt-1 text-sm text-red-600">
                    {{ editForm.errors.status }}
                  </p>
                </div>

                <!-- Position -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                  <input
                    v-model.number="editForm.position"
                    type="number"
                    min="1"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                  />
                  <p v-if="editForm.errors.position" class="mt-1 text-sm text-red-600">
                    {{ editForm.errors.position }}
                  </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                  <button
                    type="button"
                    @click="closeEditModal"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    :disabled="editForm.processing"
                    class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ editForm.processing ? "Updating..." : "Update Image" }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </Teleport>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Custom styles for drag and drop */
.cursor-move {
  cursor: move;
}

/* Smooth transitions */
* {
  transition-property: transform, opacity;
  transition-duration: 200ms;
}

button{
    cursor: pointer;
}
</style>