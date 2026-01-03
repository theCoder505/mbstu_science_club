<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

interface Application {
  id: number;
  applicant_name: string;
  email: string;
  designation: string;
  member_since: string;
  member_till: string;
  certificate_status: string;
  issue_date: string | null;
  certificate_file: string;
}

const props = defineProps<{
  applications: Application[];
  advisor: any;
}>();

usePage();
const showModal = ref(false);
const selectedApplication = ref<Application | null>(null);

const viewApplication = (application: Application) => {
  selectedApplication.value = application;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedApplication.value = null;
  
  // Remove certificate parameter from URL after closing modal
  const url = new URL(window.location.href);
  if (url.searchParams.has('certificate')) {
    url.searchParams.delete('certificate');
    window.history.replaceState({}, '', url.toString());
  }
};

const approveApplication = (id: number) => {
  if (confirm("Are you sure you want to approve this application?")) {
    router.post(
      "/advisor/approve-application",
      { id },
      {
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
        },
      }
    );
  }
};

const getStatusColor = (status: string) => {
  switch (status.toLowerCase()) {
    case "approved":
      return "bg-green-100 text-green-800";
    case "pending":
      return "bg-yellow-100 text-yellow-800";
    case "rejected":
      return "bg-red-100 text-red-800";
    default:
      return "bg-gray-100 text-gray-800";
  }
};

const formatDate = (date: string | null) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

// Check for certificate parameter in URL on mount
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const certificateId = urlParams.get('certificate');
  
  if (certificateId) {
    // Find the application with matching ID
    const application = props.applications.find(
      app => app.id === parseInt(certificateId)
    );
    
    if (application) {
      // Open modal with the found application
      viewApplication(application);
    } else {
      // If application not found, show a notification or console log
      console.warn(`Application with ID ${certificateId} not found`);
    }
  }
});

</script>

<template>
  <Head title="Advisor Dashboard" />
  <WebLayout>
    <div class="min-h-screen bg-slate-900 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h3 class="font-bold text-3xl text-yellow-500">Advisor Dashboard</h3>
            <p class="text-gray-300 mt-2">Welcome back, {{ advisor?.advisor_name }}!</p>
          </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="px-6 py-4 bg-gradient-to-r from-yellow-300 to-yellow-400">
            <h4 class="text-xl font-semibold text-gray-900">Certificate Applications</h4>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Applicant Name
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Email
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Designation
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Status
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Issue Date
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="application in applications"
                  :key="application.id"
                  class="hover:bg-gray-50"
                >
                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize"
                  >
                    {{ application.applicant_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <a class="text-blue-500" :href="'mailto:'+application.email">{{ application.email }}</a>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ application.designation }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                      :class="getStatusColor(application.certificate_status)"
                    >
                      {{ application.certificate_status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(application.issue_date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button
                      @click="viewApplication(application)"
                      class="text-yellow-600 hover:text-yellow-900 font-semibold"
                    >
                      View Details
                    </button>
                  </td>
                </tr>
                <tr v-if="applications.length === 0">
                  <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    No applications found
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="showModal"
          class="fixed inset-0 z-50 overflow-y-auto"
          @click.self="closeModal"
        >
          <div
            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
          >
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-[#00000090]"></div>

            <!-- Modal panel -->
            <div
              class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-20"
            >
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-2xl font-bold text-gray-900">Application Details</h3>
                  <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg
                      class="h-6 w-6"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>

                <div v-if="selectedApplication" class="mt-4">
                  <!-- Application Info Grid -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Applicant Name</label
                      >
                      <p class="mt-1 text-sm text-gray-900">
                        {{ selectedApplication.applicant_name }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ selectedApplication.email }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Designation</label
                      >
                      <p class="mt-1 text-sm text-gray-900">
                        {{ selectedApplication.designation }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Member Since</label
                      >
                      <p class="mt-1 text-sm text-gray-900">
                        {{ selectedApplication.member_since }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Member Till</label
                      >
                      <p class="mt-1 text-sm text-gray-900">
                        {{ selectedApplication.member_till }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Status</label
                      >
                      <span
                        class="mt-1 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="getStatusColor(selectedApplication.certificate_status)"
                      >
                        {{ selectedApplication.certificate_status }}
                      </span>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Issue Date</label
                      >
                      <p class="mt-1 text-sm text-gray-900">
                        {{ formatDate(selectedApplication.issue_date) }}
                      </p>
                    </div>
                  </div>

                  <!-- Certificate Preview -->
                  <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                      >Certificate Preview</label
                    >
                    <div class="border rounded-lg p-4 bg-gray-50">
                      <img
                        v-if="selectedApplication.certificate_file"
                        :src="`/storage/${selectedApplication.certificate_file}`"
                        alt="Certificate"
                        class="w-full h-auto rounded-lg shadow-md"
                      />
                      <p v-else class="text-center text-gray-500">
                        No certificate file available
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal Footer -->
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                  v-if="selectedApplication?.certificate_status !== 'approved'"
                  @click="approveApplication(selectedApplication!.id)"
                  type="button"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Approve Application
                </button>
                <button
                  @click="closeModal"
                  type="button"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </WebLayout>
</template>

<style scoped>
button {
  cursor: pointer;
}
</style>