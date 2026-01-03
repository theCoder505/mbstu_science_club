<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    data: string;
    webSettings: any;
    name: string;
}>();

// Format and sanitize rich text content
const formattedContent = ref('');

onMounted(() => {
    // Ensure rich text content is properly formatted
    if (props.data) {
        formattedContent.value = props.data
            .replace(/<p class="ql-align-center">/g, '<p class="text-center">')
            .replace(/<p class="ql-align-right">/g, '<p class="text-right">')
            .replace(/<p class="ql-align-justify">/g, '<p class="text-justify">')
            .replace(/class="ql-size-large"/g, 'class="text-3xl"')
            .replace(/class="ql-size-huge"/g, 'class="text-4xl"')
            .replace(/class="ql-size-small"/g, 'class="text-sm"');
    }
});
</script>

<template>
    <Head title="Our Privacy Policies" />
    <WebLayout>
        <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block">Privacy Policy</span>
                        <span class="block text-indigo-600 mt-2">{{ name }}</span>
                    </h1>
                    <div class="mt-6">
                        <div class="inline-flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="ml-2 text-sm text-gray-500">Last Updated: {{ new Date().toLocaleDateString() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Content Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-8 py-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 class="ml-3 text-2xl font-bold text-white">Your Privacy Matters</h2>
                        </div>
                    </div>

                    <!-- Rich Text Content -->
                    <div class="px-8 py-10">
                        <div 
                            v-if="formattedContent" 
                            class="prose prose-lg max-w-none prose-indigo"
                            v-html="formattedContent"
                        ></div>
                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No privacy policy content</h3>
                            <p class="mt-1 text-sm text-gray-500">Privacy policy content will be displayed here.</p>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Email:</span>
                                    </div>
                                    <p class="mt-1 text-gray-900 font-medium">{{ webSettings?.contact_email || 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Phone:</span>
                                    </div>
                                    <p class="mt-1 text-gray-900 font-medium">{{ webSettings?.phone_number || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        If you have any questions about our privacy policy, please don't hesitate to contact us.
                    </p>
                </div>
            </div>
        </div>
    </WebLayout>
</template>