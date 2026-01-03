<script setup lang="ts">
import Input from "@/components/Input.vue";
import WebLayout from "@/layouts/WebLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

interface PageProps {
  applicant_mail?: string;
  record?: {
    certificate_status: string;
  };
  flash?: {
    success?: string;
  };
}

const page = usePage<PageProps>();

// Access props properly with type safety
const initialEmail = computed(() => {
  return page.props.applicant_mail || "";
});

// Track record should be a computed property to react to changes
const trackRecord = computed(() => {
  return page.props.record?.certificate_status || "";
});

const form = useForm({
  email: initialEmail.value,
});

// Store the email that was successfully used for tracking
const trackedEmail = ref<string>(initialEmail.value);

const statusSteps = [
  { id: 1, name: "Pending", description: "Application received and awaiting initial review" },
  { id: 2, name: "Verified", description: "Application details verified by administrator" },
  { id: 3, name: "Approved/Revision/Declined", description: "Final decision made" },
];

// Current active step based on status
const currentStep = computed(() => {
  if (!trackRecord.value) return 0;
  
  const status = trackRecord.value.toLowerCase();
  if (status === 'pending') return 1;
  if (status === 'verified') return 2;
  if (status === 'approved' || status === 'revision' || status === 'declined') return 3;
  return 0;
});

// Get current status message
const statusMessage = computed(() => {
  if (!trackRecord.value) return null;
  
  const status = trackRecord.value.toLowerCase();
  switch(status) {
    case 'pending':
      return 'Your application is pending initial review.';
    case 'verified':
      return 'Your application has been verified. Final decision is pending.';
    case 'approved':
      return 'Congratulations! Your certificate has been approved. You can now download your certificate.';
    case 'revision':
      return 'Your application requires revision. Please check your email for details.';
    case 'declined':
      return 'Your application has been declined. Please contact support for more information.';
    default:
      return null;
  }
});

// Get status color
const statusColor = computed(() => {
  if (!trackRecord.value) return 'gray';
  
  const status = trackRecord.value.toLowerCase();
  switch(status) {
    case 'pending':
      return 'yellow';
    case 'verified':
      return 'blue';
    case 'approved':
      return 'green';
    case 'revision':
      return 'orange';
    case 'declined':
      return 'red';
    default:
      return 'gray';
  }
});

// Check if certificate can be downloaded
const canDownloadCertificate = computed(() => {
  return trackRecord.value.toLowerCase() === 'approved';
});

const submit = () => {
  form.post("/track-certificate", {
    preserveScroll: true,
    onSuccess: () => {
      // Store the email that was successfully used for tracking
      if (trackRecord.value) {
        trackedEmail.value = form.email;
      }
    },
  });
};

// Function to download certificate
const downloadCertificate = () => {
  if (!canDownloadCertificate.value || !trackedEmail.value) {
    alert('Please track your application first to get the email.');
    return;
  }
  
  const downloadForm = useForm({
    email: trackedEmail.value,
  });
  
  downloadForm.post("/download-certificate", {
    preserveScroll: true,
    onSuccess: (response) => {
      // if (response.props.download_url) {
      //   window.open(response.props.download_url, '_blank');
      // }
      console.log(response);
    },
    onError: () => {
      alert('Failed to download certificate. Please try again.');
    },
  });
};

// Check if form was just submitted
const showNoRecordMessage = computed(() => {
  return form.recentlySuccessful && !trackRecord.value && form.email;
});

</script>

<template>
  <Head title="Track Your Certificate" />

  <WebLayout>
    <div class="min-h-screen py-8 px-4 flex justify-center items-center bg-yellow-300">
      <div class="lg:w-2xl mx-auto">
        <!-- Status Display Section -->
        <div v-if="trackRecord" class="mb-8 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-teal-900">
              Application Status
            </h2>
            <span class="px-4 py-2 rounded-full text-sm font-medium"
              :class="{
                'bg-yellow-100 text-yellow-800': statusColor === 'yellow',
                'bg-blue-100 text-blue-800': statusColor === 'blue',
                'bg-green-100 text-green-800': statusColor === 'green',
                'bg-orange-100 text-orange-800': statusColor === 'orange',
                'bg-red-100 text-red-800': statusColor === 'red',
                'bg-gray-100 text-gray-800': statusColor === 'gray'
              }">
              {{ trackRecord.toUpperCase() }}
            </span>
          </div>
          
          <!-- Status Message -->
          <div v-if="statusMessage" class="mb-8 p-4 bg-gray-50 rounded-lg">
            <p class="text-lg text-gray-700">{{ statusMessage }}</p>
            
            <!-- Download Certificate Button (only for approved status) -->
            <div v-if="canDownloadCertificate" class="mt-4">
              <button
                @click="downloadCertificate"
                class="px-6 py-3 bg-gradient-to-r from-yellow-300 to-yellow-400 text-black rounded-lg hover:from-yellow-400 hover:to-yellow-300 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-md flex items-center justify-center"
              >
                <span class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Download Certificate
                </span>
              </button>
              
              <div class="mt-3 p-3 bg-teal-50 rounded-lg border border-teal-100">
                <p class="text-sm text-gray-600">
                  Certificate will be downloaded for: <strong>{{ trackedEmail }}</strong>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                  Status: <span class="font-semibold uppercase">{{ trackRecord }}</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Progress Steps -->
          <div class="relative">
            <!-- Progress Line -->
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200"></div>
            <div class="absolute top-5 left-0 h-1 bg-teal-500 transition-all duration-500"
                 :style="{ width: `${((currentStep - 1) / (statusSteps.length - 1)) * 100}%` }"></div>

            <!-- Steps -->
            <div class="relative flex justify-between">
              <div v-for="step in statusSteps" :key="step.id" class="flex flex-col items-center">
                <!-- Step Circle -->
                <div class="relative z-10 w-10 h-10 rounded-full border-4 bg-white flex items-center justify-center mb-2"
                     :class="{
                       'border-teal-500': currentStep >= step.id,
                       'border-gray-300': currentStep < step.id
                     }">
                  <span v-if="currentStep >= step.id" class="text-teal-500 font-bold">
                    {{ step.id }}
                  </span>
                  <span v-else class="text-gray-400">
                    {{ step.id }}
                  </span>
                </div>
                
                <!-- Step Info -->
                <div class="text-center max-w-[150px]">
                  <h4 class="font-semibold text-sm"
                      :class="{
                        'text-teal-700': currentStep >= step.id,
                        'text-gray-500': currentStep < step.id
                      }">
                    {{ step.name }}
                  </h4>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ step.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Current Step Indicator -->
          <div v-if="currentStep > 0" class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center">
              <div class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-teal-900">
                  Currently at: <span class="font-bold">{{ statusSteps[currentStep - 1]?.name }}</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <Form class="space-y-8" @submit.prevent="submit">
            <div class="space-y-2">
              <h2 class="text-2xl font-bold text-teal-900 pb-3 border-b border-teal-100 text-center">
                {{ trackRecord ? 'Track Another Certificate' : 'Track Your Certificate' }}
              </h2>

              <div class="grid gap-6">
                <div>
                  <Input
                    placeholder="Enter your email address"
                    v-model="form.email"
                    :message="form.errors.email"
                    type="email"
                  />
                </div>

                <button
                  type="submit"
                  class="w-full sm:w-auto px-10 py-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-lg rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px] mx-auto"
                  :disabled="form.processing"
                >
                  <span v-if="!form.processing" class="flex items-center">
                    <svg
                      class="w-5 h-5 mr-3"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                    {{ trackRecord ? 'Track Another' : 'Track Certificate' }}
                  </span>
                  <span v-else class="flex items-center">
                    <svg
                      class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
                      />
                      <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                      />
                    </svg>
                    Processing...
                  </span>
                </button>
              </div>
              
              <!-- Success Message from apply_certificate -->
              <div v-if="page.props.flash?.success && !trackRecord" 
                   class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                      {{ page.props.flash.success }}
                    </p>
                    <p v-if="page.props.applicant_mail" class="text-sm text-green-700 mt-1">
                      You can track your application using: <strong>{{ page.props.applicant_mail }}</strong>
                    </p>
                  </div>
                </div>
              </div>
              
              <!-- No Record Found Message -->
              <div v-if="showNoRecordMessage" 
                   class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.162 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                      No application found with email: <strong>{{ form.email }}</strong>
                    </p>
                    <p class="text-sm text-yellow-700 mt-1">
                      Please check the email address or contact support if you believe this is an error.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </Form>
        </div>
      </div>
    </div>
  </WebLayout>
</template>