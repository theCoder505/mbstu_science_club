<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { Image as ImageIcon, Edit, Trash2, Plus } from "lucide-vue-next";
import { debounce } from "lodash";

interface Template {
  id: number;
  template_name: string;
  template_image: string;
  created_at: string;
  updated_at: string;
}

interface Props {
  templates: Template[];
  searchTerm?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Certificate Templates",
    href: "/templates",
  },
];

const search = ref(props.searchTerm || "");

watch(
  search,
  debounce(
    (query) => router.get("/templates", { search: query }, { preserveState: true }),
    300
  )
);

// Create/Edit Modal
const showModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null as number | null,
  template_name: "",
  template_image: null as File | null,
  existing_image: "",
});
const imagePreview = ref<string | null>(null);

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    template_name: "",
    template_image: null,
    existing_image: "",
  };
  imagePreview.value = null;
  showModal.value = true;
};

const openEditModal = (template: Template) => {
  isEditing.value = true;
  form.value = {
    id: template.id,
    template_name: template.template_name,
    template_image: null,
    existing_image: template.template_image,
  };
  imagePreview.value = `/storage/${template.template_image}`;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  form.value = {
    id: null,
    template_name: "",
    template_image: null,
    existing_image: "",
  };
  imagePreview.value = null;
};

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    form.value.template_image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const submitForm = () => {
  const formData = new FormData();
  formData.append("template_name", form.value.template_name);

  if (form.value.template_image) {
    formData.append("template_image", form.value.template_image);
  } else if (isEditing.value && form.value.existing_image) {
    formData.append("template_image", form.value.existing_image);
  }

  if (isEditing.value && form.value.id) {
    formData.append("id", form.value.id.toString());
    router.post("/templates/update", formData, {
      onSuccess: () => {
        closeModal();
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Template updated successfully!",
        });
      },
      onError: (errors) => {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: (Object.values(errors)[0] as string) || "Failed to update template",
        });
      },
    });
  } else {
    router.post("/templates/store", formData, {
      onSuccess: () => {
        closeModal();
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Template created successfully!",
        });
      },
      onError: (errors) => {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: (Object.values(errors)[0] as string) || "Failed to create template",
        });
      },
    });
  }
};

// Delete Modal
const showDeleteModal = ref(false);
const templateToDelete = ref<number | null>(null);

const openDeleteModal = (id: number) => {
  templateToDelete.value = id;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  templateToDelete.value = null;
};

const confirmDelete = () => {
  if (templateToDelete.value) {
    router.delete(`/templates/${templateToDelete.value}`, {
      onSuccess: () => {
        closeDeleteModal();
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: "Template deleted successfully!",
        });
      },
      onError: () => {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Failed to delete template",
        });
      },
    });
  }
};

const getDate = (date: string) => {
  return new Date(date).toLocaleDateString("en-UK", {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  });
};
</script>

<template>
  <Head title="Certificate Templates" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div>
      <h1 class="text-center text-2xl my-6 font-bold capitalize text-teal">
        Certificate Templates
      </h1>
    </div>

    <div class="mx-4 w-full lg:w-11/12 overflow-x-auto lg:mx-auto mb-12">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <input
          type="search"
          placeholder="Search templates..."
          v-model="search"
          class="border px-4 py-3 rounded-lg border-teal-600 outline-0 w-[250px]"
        />

        <div class="flex gap-2">
          <Link
            href="/templates/create"
            class="bg-gradient-to-r from-yellow-300 to-yellow-400 rounded-xl px-4 py-2.5 flex items-center gap-2 hover:from-yellow-400 hover:to-yellow-300 transition-all duration-300 shadow-lg hover:shadow-xl"
          >
            <Plus class="w-5 h-5" />
            Design A Template
          </Link>

          <button
            @click="openCreateModal"
            class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium whitespace-nowrap"
          >
            <Plus class="w-4 h-4" />
            Add Template
          </button>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-max">
            <thead class="bg-gradient-to-r from-teal-500 to-teal-600">
              <tr>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <span>#</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <ImageIcon class="w-4 h-4" />
                    <span>Template Image</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <span>Template Name</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <span>Created At</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-center font-semibold text-white tracking-wider">
                  <div class="flex items-center justify-center space-x-2">
                    <span>Actions</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
              <tr v-if="templates.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  No templates found. Create your first template to get started.
                </td>
              </tr>
              <tr
                v-for="(template, index) in templates"
                :key="template.id"
                :class="[
                  'hover:bg-teal-50 transition-colors duration-150 border-b-2 border-gray-200',
                  (index as number) % 2 == 0 ? 'bg-white' : 'bg-gray-50',
                ]"
              >
                <!-- Index -->
                <td class="py-4 px-6">
                  <div class="font-medium text-gray-900">
                    {{ index + 1 }}
                  </div>
                </td>

                <!-- Template Image -->
                <td class="py-4 px-6">
                  <div class="flex items-center">
                    <img
                      :src="`/storage/${template.template_image}`"
                      :alt="template.template_name"
                      class="w-[100px] rounded-lg border-2 border-gray-200 shadow-sm"
                    />
                  </div>
                </td>

                <!-- Template Name -->
                <td class="py-4 px-6">
                  <div class="font-medium text-gray-900">
                    {{ template.template_name }}
                  </div>
                </td>

                <!-- Created At -->
                <td class="py-4 px-6">
                  <div class="text-sm font-medium text-gray-700">
                    {{ getDate(template.created_at) }}
                  </div>
                </td>

                <!-- Actions -->
                <td class="py-4 px-6">
                  <div class="flex items-center justify-center space-x-2">
                    <button
                      @click="openEditModal(template)"
                      class="p-2 rounded-lg hover:bg-teal-50 text-teal-600 hover:text-teal-700 transition-colors group"
                      title="Edit"
                    >
                      <Edit
                        class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer"
                      />
                    </button>
                    <button
                      @click="openDeleteModal(template.id)"
                      class="p-2 rounded-lg hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors group"
                      title="Delete"
                    >
                      <Trash2
                        class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer"
                      />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <!-- Background overlay -->
        <div
          class="fixed inset-0 bg-[#00000070] transition-opacity"
          @click="closeModal"
        ></div>

        <!-- Modal panel -->
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-20"
        >
          <form @submit.prevent="submitForm">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ isEditing ? "Edit Template" : "Add New Template" }}
              </h3>

              <div class="mb-4">
                <label
                  for="template_name"
                  class="block text-sm font-medium text-gray-700 mb-2"
                >
                  Template Name *
                </label>
                <input
                  id="template_name"
                  v-model="form.template_name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                  placeholder="Enter template name"
                />
              </div>

              <div class="mb-4">
                <label
                  for="template_image"
                  class="block text-sm font-medium text-gray-700 mb-2"
                >
                  Template Image {{ isEditing ? "" : "*" }}
                </label>
                <input
                  id="template_image"
                  type="file"
                  accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                  @change="handleImageChange"
                  :required="!isEditing"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                />
                <p class="mt-1 text-xs text-gray-500">
                  Accepted formats: JPEG, PNG, JPG, GIF, SVG (Max: 20MB)
                </p>
              </div>

              <!-- Image Preview -->
              <div v-if="imagePreview" class="mb-4">
                <div class="flex justify-center">
                  <img
                    :src="imagePreview"
                    alt="Preview"
                    class="w-full rounded-lg border-2 border-gray-300 shadow-sm"
                  />
                </div>
              </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="submit"
                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm"
              >
                {{ isEditing ? "Update" : "Create" }}
              </button>
              <button
                type="button"
                @click="closeModal"
                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <!-- Background overlay -->
        <div
          class="fixed inset-0 bg-[#00000070] transition-opacity"
          @click="closeDeleteModal"
        ></div>

        <!-- Modal panel -->
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-20"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-red-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Delete Template
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this template? This action cannot be
                    undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="confirmDelete"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Delete
            </button>
            <button
              type="button"
              @click="closeDeleteModal"
              class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
button {
  cursor: pointer;
}
</style>
