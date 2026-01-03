<script setup lang="ts">
import Input from "@/components/Input.vue";
import WebLayout from "@/layouts/WebLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

const form = useForm({
  applicant_name: null,
  email: null,
  designation: null,
  member_since: null,
  member_till: null,
  impact: "",
});

const submit = () => {
  form.post("/apply-certificate", {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};
</script>

<template>
  <Head title="Apply For Certificate" />

  <WebLayout>
    <div class="min-h-screen py-8 px-4 bg-yellow-300">
      <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-10">
          <div
            class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6"
          >
            <svg
              class="w-10 h-10 text-teal-600"
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
          </div>
          <h1 class="text-4xl font-bold text-teal-900 mb-3">
            Apply For Your Certificate
          </h1>
          <p class="text-teal-900 text-lg max-w-2xl mx-auto">
            Fill out the form below to apply for your membership certificate. All fields
            marked with * are required.
          </p>
        </div>

        <!-- Form Container -->
        <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-2xl shadow-xl p-8 md:p-10 border border-teal-200">
          <Form class="space-y-8" @submit.prevent="submit">
            <!-- Personal Information Section -->
            <div class="space-y-6">
              <h2
                class="text-2xl font-bold text-teal-900 pb-3 border-b border-teal-100"
              >
                Personal Information
              </h2>

              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-teal-700 mb-2">
                    Applicant Name *
                  </label>
                  <Input
                    placeholder="Enter your full name"
                    v-model="form.applicant_name"
                    :message="form.errors.applicant_name"
                    type="text"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-teal-700 mb-2">
                    Email Address *
                  </label>
                  <Input
                    placeholder="Enter your email address"
                    v-model="form.email"
                    :message="form.errors.email"
                    type="email"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-teal-700 mb-2">
                  Designation *
                </label>
                <Input
                  placeholder="Enter your designation"
                  v-model="form.designation"
                  :message="form.errors.designation"
                  type="text"
                />
              </div>
            </div>

            <!-- Membership Duration Section -->
            <div class="space-y-6">
              <h2
                class="text-2xl font-bold text-teal-900 pb-3 border-b border-teal-100"
              >
                Membership Duration
              </h2>

              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-teal-700 mb-2">
                    Member Since *
                  </label>
                  <Input
                    placeholder="YYYY-MM-DD"
                    v-model="form.member_since"
                    :message="form.errors.member_since"
                    type="date"
                  />
                  <p class="text-sm text-teal-600 mt-2">
                    When did you become a member?
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-teal-700 mb-2">
                    Member Till *
                  </label>
                  <Input
                    placeholder="YYYY-MM-DD"
                    v-model="form.member_till"
                    :message="form.errors.member_till"
                    type="date"
                  />
                  <p class="text-sm text-teal-600 mt-2">Until when you were a member?</p>
                </div>
              </div>
            </div>

            <!-- Impact Statement Section -->
            <div class="space-y-6">
              <h2
                class="text-2xl font-bold text-teal-900 pb-3 border-b border-teal-100"
              >
                Impact Statement
              </h2>

              <div>
                <label class="block text-sm font-medium text-teal-700 mb-2">
                  Your Impact (Optional)
                  <span class="text-teal-500 font-normal ml-1">Max 500 characters</span>
                </label>
                <div class="relative">
                  <textarea
                    v-model="form.impact"
                    :maxlength="500"
                    placeholder="Describe your contributions and impact during your membership..."
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 min-h-[120px]"
                    :class="
                      form.errors.impact
                        ? 'border-red-500 focus:ring-red-500'
                        : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'
                    "
                  ></textarea>
                </div>
                <div  class="lg:flex justify-between flex-row-reverse">
                  <div class="text-right mt-2">
                    <span
                      class="text-sm"
                      :class="
                        form.impact?.length >= 450 ? 'text-teal-600' : 'text-teal-500'
                      "
                    >
                      {{ form.impact?.length || 0 }}/500
                    </span>
                  </div>
                  <small v-if="form.errors.impact" class="text-red-500 block mt-2">{{
                    form.errors.impact
                  }}</small>
                <p class="text-sm text-teal-600 mt-2">
                  Share your achievements and contributions during your membership period.
                </p>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-teal-100">
              <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-teal-700">
                  <svg
                    class="inline-block w-4 h-4 mr-2"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                      clip-rule="evenodd"
                    />
                  </svg>
                  All information provided will be kept confidential
                </div>

                <button
                  type="submit"
                  class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-lg rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:transform-none disabled:hover:shadow-lg flex items-center justify-center min-w-[200px]"
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
                    Apply For Certificate
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
            </div>
          </Form>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
          <div class="inline-flex items-center space-x-2 text-teal-700">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                clip-rule="evenodd"
              />
            </svg>
            <span>Processing time: 3-5 business days</span>
          </div>
        </div>
      </div>
    </div>
  </WebLayout>
</template>

