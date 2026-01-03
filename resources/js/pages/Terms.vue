<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    data: string;
    webSettings: any;
    name: string;
}>();

const formattedContent = ref('');

onMounted(() => {
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
    <Head title="Our Terms & Conditions" />
    <WebLayout>
        <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <div class="relative">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Terms & Conditions</span>
                            <span class="block text-blue-600 mt-2">{{ name }}</span>
                        </h1>
                        <div class="absolute -top-2 -right-2">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Legal Document
                            </span>
                        </div>
                    </div>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                        Please read these terms and conditions carefully before using our services
                    </p>
                </div>

                <!-- Main Content Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100">
                    <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-cyan-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="ml-3 text-2xl font-bold text-white">Legal Agreement</h2>
                            </div>
                            <div class="text-white text-sm bg-blue-700 px-3 py-1 rounded-full">
                                Binding Contract
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="px-8 py-10">
                        <!-- Important Notice -->
                        <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        <p>By accessing and using {{ name }}'s services, you agree to be bound by these terms and conditions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rich Text Content -->
                        <div class="prose prose-lg max-w-none prose-blue">
                            <div 
                                v-if="formattedContent" 
                                class="border-2 border-dashed border-gray-200 rounded-lg p-8"
                                v-html="formattedContent"
                            ></div>
                            <div v-else class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Terms & Conditions Content</h3>
                                <p class="mt-2 text-gray-500">The terms and conditions content will be displayed here.</p>
                            </div>
                        </div>

                        <!-- Acceptance Section -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Acceptance of Terms</h3>
                                <p class="text-gray-600 mb-4">
                                    Your use of our services constitutes acceptance of these terms. If you do not agree with any part of these terms, you must not use our services.
                                </p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Last updated: {{ new Date().toLocaleDateString('en-US', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    }) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact for Questions -->
                        <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Questions About These Terms?</h4>
                                    <p class="mt-1 text-gray-600">
                                        Contact us at <a :href="`mailto:${webSettings?.contact_email}`" class="text-blue-600 hover:text-blue-800 font-medium">{{ webSettings?.contact_email || 'our contact email' }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>