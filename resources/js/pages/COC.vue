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
    <Head title="MBSTUSC Code Of Conduct & Constitution" />
    <WebLayout>
        <div class="min-h-screen bg-gradient-to-b from-emerald-50 to-white py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <!-- Header with Logo-like Design -->
                <div class="text-center mb-12">
                    <div class="inline-block p-6 bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl shadow-lg mb-6">
                        <svg class="h-16 w-16 text-white mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block">Code of Conduct &</span>
                        <span class="block text-emerald-600 mt-2">Constitution</span>
                    </h1>
                    <p class="mt-4 max-w-3xl text-xl text-gray-600 mx-auto">
                        Guiding principles and regulations for all members of {{ name }}
                    </p>
                </div>

                <!-- Main Content Area -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-emerald-100">
                    <!-- Header Banner -->
                    <div class="px-8 py-8 bg-gradient-to-r from-emerald-600 to-green-700">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-2">{{ name }}</h2>
                                <p class="text-emerald-100">Official Code of Conduct Document</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                                    <svg class="h-5 w-5 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-white text-sm">Effective Date: {{ new Date().toLocaleDateString() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Sections -->
                    <div class="grid gap-0">

                        <!-- Main Content -->
                        <div class="lg:col-span-3 p-8">
                            <!-- Preamble -->
                            <div id="preamble" class="mb-10">
                                <div class="prose prose-lg max-w-none prose-emerald">
                                    <div 
                                        v-if="formattedContent" 
                                        class="bg-gray-50 rounded-xl p-6 border border-gray-200"
                                        v-html="formattedContent"
                                    ></div>
                                    <div v-else class="text-center py-12">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">Code of Conduct Content</h3>
                                        <p class="mt-2 text-gray-500">The Code of Conduct and Constitution content will be displayed here.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Principles Section -->
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Our Core Principles</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gradient-to-br from-emerald-50 to-white p-6 rounded-xl border border-emerald-100">
                                        <div class="flex items-center mb-4">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <h4 class="ml-3 text-lg font-semibold text-gray-900">Integrity</h4>
                                        </div>
                                        <p class="text-gray-600">Maintaining honesty and strong moral principles in all activities.</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-emerald-50 to-white p-6 rounded-xl border border-emerald-100">
                                        <div class="flex items-center mb-4">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <h4 class="ml-3 text-lg font-semibold text-gray-900">Community</h4>
                                        </div>
                                        <p class="text-gray-600">Fostering a supportive and inclusive environment for all members.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Section -->
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl p-6">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Questions or Concerns?</h4>
                                            <p class="text-gray-600 mt-1">Contact the executive committee for clarification on any aspect of this document.</p>
                                        </div>
                                        <div class="mt-4 md:mt-0">
                                            <a :href="`mailto:${webSettings?.contact_email}`" 
                                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Contact Us
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        This document is binding for all members of {{ name }} and subject to periodic review.
                    </p>
                </div>
            </div>
        </div>
    </WebLayout>
</template>