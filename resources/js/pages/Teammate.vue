<script setup lang="ts">
import WebLayout from "@/layouts/WebLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Define the props interface
interface TeammateProps {
    teammate: {
        id: number;
        teammate_image: string;
        teammate_name: string;
        department: string;
        designation: string;
        membership_period: string;
        small_desc: string;
        details: string;
        facebook_link: string | null;
        linkedin_link: string | null;
    } | null;
}

// Define props
defineProps<TeammateProps>();
const getImageUrl = (imagePath: string | null) => {
    if (!imagePath) return '/images/default-avatar.png';
    return `/storage/${imagePath}`;
};
</script>

<template>
    <Head :title="teammate ? teammate.teammate_name : 'Teammate'" />
    <WebLayout>
        <div class="container mx-auto px-4 py-8 bg-yellow-300">
            <!-- Loading state -->
            <div v-if="!teammate" class="text-center py-12">
                <p class="text-gray-900">Loading teammate information...</p>
            </div>

            <!-- Teammate Profile -->
            <div v-else class="max-w-6xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="mb-8">
                    <ol class="flex items-center space-x-2 text-sm text-gray-900">
                        <li>
                            <Link href="/" class="hover:text-blue-900">MBTUSC</Link>
                        </li>
                        <li>
                            <span class="mx-2">/</span>
                        </li>
                        <li class="text-gray-900 font-medium">
                            {{ teammate.teammate_name }}
                        </li>
                    </ol>
                </nav>

                <!-- Profile Card -->
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-lg shadow-lg overflow-hidden">
                    <div class="md:flex">
                        <!-- Image Section -->
                        <div class="md:w-1/3 p-6 md:p-8">
                            <div class="relative">
                                <img 
                                    :src="getImageUrl(teammate.teammate_image)" 
                                    :alt="teammate.teammate_name"
                                    class="w-full h-auto rounded-lg object-cover shadow-md"
                                />
                                <!-- Social Links -->
                                <div v-if="teammate.facebook_link || teammate.linkedin_link" 
                                     class="flex justify-center space-x-4 mt-6">
                                    <a 
                                        v-if="teammate.facebook_link"
                                        :href="teammate.facebook_link"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-white hover:text-blue-200 transition-colors"
                                    >
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a 
                                        v-if="teammate.linkedin_link"
                                        :href="teammate.linkedin_link"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-white hover:text-blue-200 transition-colors"
                                    >
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="md:w-2/3 p-6 md:p-8">
                            <!-- Name and Designation -->
                            <div class="mb-6">
                                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                                    {{ teammate.teammate_name }}
                                </h1>
                                <div class="flex items-center space-x-4 text-lg text-gray-700 mb-4">
                                    <span class="font-semibold text-yellow-300">
                                        {{ teammate.designation }}
                                    </span>
                                    <span class="bg-yellow-400 w-2 h-2 rounded-full mt-1"></span>
                                    <span class="text-yellow-300">
                                        {{ teammate.department }}
                                    </span>
                                </div>
                            </div>

                            <!-- Membership Period -->
                            <div class="mb-6">
                                <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">Member since {{ teammate.membership_period }}</span>
                                </div>
                            </div>

                            <!-- Short Description -->
                            <div class="mb-8">
                                <p class="text-lg text-gray-200 italic border-l-4 border-yellow-500 pl-4 py-2">
                                    {{ teammate.small_desc }}
                                </p>
                            </div>

                            <!-- Detailed Information -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-white mb-4">About</h2>
                                <div class="prose max-w-none">
                                    <!-- Using v-html for HTML content or plain text -->
                                    <div v-if="teammate.details.includes('<')" 
                                         v-html="teammate.details"
                                         class="text-gray-200 leading-relaxed">
                                    </div>
                                    <!-- For plain text with line breaks -->
                                    <template v-else>
                                        <div v-for="(paragraph, index) in teammate.details.split('\n\n')" 
                                             :key="index"
                                             class="mb-4">
                                            <p class="text-gray-200 leading-relaxed">{{ paragraph }}</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>

<style scoped>
.prose {
    line-height: 1.75;
}

.prose p {
    margin-bottom: 1rem;
}

.prose p:last-child {
    margin-bottom: 0;
}
</style>