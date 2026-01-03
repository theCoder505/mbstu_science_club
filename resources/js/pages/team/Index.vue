<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { Plus, Pencil, Trash, User, Briefcase, Building, Calendar, Award, ExternalLink, Facebook, Linkedin } from "lucide-vue-next";
import { ref, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";
import { debounce } from "lodash";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Teammates",
    href: "/manage-team",
  },
];

defineProps({
  teammates: {
    type: Array,
    default: () => [],
  },
});

const getDate = (date: any) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-uk", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const askToDelete = (id: any) => {
  if (confirm("Are you sure you want to delete this teammate?")) {
    router.post('/manage-team/destroy', { id }, { preserveScroll: true });
  }
};

const search = ref('');

watch(
  search,
  debounce(
    (query) => router.get("/manage-team", { search: query }, { preserveState: true }),
    300
  )
);

// Function to truncate text
const truncateText = (text: string, length: number) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

// Function to get initials
const getInitials = (name: string) => {
  if (!name) return '?';
  return name.split(' ').map(n => n[0]).join('').toUpperCase();
};
</script>

<template>
  <Head title="Manage Teammates" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-6">
      <!-- Header Section -->
      <div class="mb-8">
        <h1 class="text-center text-3xl font-bold text-teal-700 mb-2">
          Manage Teammates
        </h1>
        <p class="text-center text-gray-600 max-w-2xl mx-auto">
          Manage your team members, their details, and professional information
        </p>
      </div>

      <!-- Action Bar -->
      <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-8">
        <Link
          :href="route('team.create')"
          class="bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-xl px-6 py-3 flex items-center gap-3 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        >
          <Plus class="w-5 h-5" />
          Add New Teammate
        </Link>
        
        <div class="flex w-full lg:w-auto">
          <div class="relative w-full lg:w-96">
            <input
              type="search"
              placeholder="Search teammates by name, department, or designation..."
              v-model="search"
              class="w-full px-4 py-3 pl-12 rounded-xl border border-teal-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all bg-white text-gray-700"
            />
            <div class="absolute left-4 top-3.5">
              <User class="w-5 h-5 text-teal-400" />
            </div>
          </div>
        </div>
      </div>

      <!-- Teammates Grid/Table -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
          <table class="w-full min-w-max">
            <thead class="bg-gradient-to-r from-teal-500 to-teal-600">
              <tr>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <User class="w-5 h-5" />
                    <span>Team Member</span>
                  </div>
                </th>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Building class="w-5 h-5" />
                    <span>Department & Role</span>
                  </div>
                </th>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Calendar class="w-5 h-5" />
                    <span>Membership</span>
                  </div>
                </th>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Award class="w-5 h-5" />
                    <span>Description</span>
                  </div>
                </th>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <ExternalLink class="w-5 h-5" />
                    <span>Social Links</span>
                  </div>
                </th>
                <th class="py-5 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Briefcase class="w-5 h-5" />
                    <span>Actions</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
              <tr
                v-for="(teammate, index) in (teammates as any[])"
                :key="teammate.id"
                :class="[
                  'hover:bg-teal-50/50 transition-colors duration-150',
                  index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30',
                ]"
              >
                <!-- Team Member Info -->
                <td class="py-5 px-6">
                  <div class="flex items-start space-x-4">
                    <div class="relative flex-shrink-0">
                      <div v-if="teammate.teammate_image" class="w-16 h-16 rounded-xl overflow-hidden border-2 border-teal-200 shadow-sm">
                        <img
                          :src="`/storage/${teammate.teammate_image}`"
                          :alt="teammate.teammate_name"
                          class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        />
                      </div>
                      <div v-else class="w-16 h-16 rounded-xl bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-200 shadow-sm flex items-center justify-center">
                        <span class="text-2xl font-bold text-teal-600">
                          {{ getInitials(teammate.teammate_name) }}
                        </span>
                      </div>
                    </div>
                    <div class="min-w-0 flex-1">
                      <h3 class="font-bold text-gray-900 text-lg capitalize mb-1">
                        {{ teammate.teammate_name }}
                      </h3>
                      <div class="flex items-center text-sm text-gray-500">
                        <Briefcase class="w-4 h-4 mr-1" />
                        <span>{{ teammate.designation }}</span>
                      </div>
                      <div class="mt-2 text-xs text-gray-400">
                        ID: {{ teammate.id }}
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Department & Role -->
                <td class="py-5 px-6">
                  <div class="space-y-2">
                    <div>
                      <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-teal-100 text-teal-800 border border-teal-200">
                        {{ teammate.department }}
                      </span>
                    </div>
                    <div class="text-sm text-gray-600">
                      {{ teammate.designation }}
                    </div>
                  </div>
                </td>

                <!-- Membership Period -->
                <td class="py-5 px-6">
                  <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg flex items-center justify-center border border-teal-200">
                      <Calendar class="w-5 h-5 text-teal-600" />
                    </div>
                    <div>
                      <div class="font-semibold text-gray-900">
                        {{ teammate.membership_period }}
                      </div>
                      <div class="text-sm text-gray-500">
                        Period
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Description -->
                <td class="py-5 px-6">
                  <div class="max-w-xs">
                    <p class="text-gray-700 text-sm line-clamp-3 mb-2">
                      {{ teammate.small_desc }}
                    </p>
                    <div class="flex items-center text-xs text-teal-600">
                      <ExternalLink class="w-3 h-3 mr-1" />
                      <span>{{ truncateText(teammate.details, 50) }}</span>
                    </div>
                  </div>
                </td>

                <!-- Social Links -->
                <td class="py-5 px-6">
                  <div class="flex items-center space-x-2">
                    <a
                      v-if="teammate.facebook_link"
                      :href="teammate.facebook_link"
                      target="_blank"
                      class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 transition-colors"
                      title="Facebook"
                    >
                      <Facebook class="w-5 h-5" />
                    </a>
                    <div v-else class="p-2 rounded-lg bg-gray-100 text-gray-400">
                      <Facebook class="w-5 h-5" />
                    </div>
                    
                    <a
                      v-if="teammate.linkedin_link"
                      :href="teammate.linkedin_link"
                      target="_blank"
                      class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 transition-colors"
                      title="LinkedIn"
                    >
                      <Linkedin class="w-5 h-5" />
                    </a>
                    <div v-else class="p-2 rounded-lg bg-gray-100 text-gray-400">
                      <Linkedin class="w-5 h-5" />
                    </div>
                  </div>
                </td>

                <!-- Actions -->
                <td class="py-5 px-6">
                  <div class="flex items-center space-x-3">
                    <Link
                      :href="route('team.edit', teammate.id)"
                      class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow hover:shadow-md transform hover:-translate-y-0.5"
                    >
                      <Pencil class="w-4 h-4" />
                      Edit
                    </Link>
                    <button
                      @click="askToDelete(teammate.id)"
                      class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow hover:shadow-md transform hover:-translate-y-0.5"
                    >
                      <Trash class="w-4 h-4" />
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden p-4">
          <div class="grid grid-cols-1 gap-4">
            <div
              v-for="teammate in (teammates as any[])"
              :key="teammate.id"
              class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex items-start space-x-4 mb-4">
                <!-- Profile Image -->
                <div class="relative flex-shrink-0">
                  <div v-if="teammate.teammate_image" class="w-20 h-20 rounded-xl overflow-hidden border-2 border-teal-200">
                    <img
                      :src="`/storage/${teammate.teammate_image}`"
                      :alt="teammate.teammate_name"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  <div v-else class="w-20 h-20 rounded-xl bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-200 flex items-center justify-center">
                    <span class="text-2xl font-bold text-teal-600">
                      {{ getInitials(teammate.teammate_name) }}
                    </span>
                  </div>
                </div>

                <!-- Basic Info -->
                <div class="flex-1 min-w-0">
                  <h3 class="font-bold text-gray-900 text-lg capitalize mb-1">
                    {{ teammate.teammate_name }}
                  </h3>
                  <div class="flex items-center text-sm text-gray-600 mb-1">
                    <Briefcase class="w-4 h-4 mr-1" />
                    <span>{{ teammate.designation }}</span>
                  </div>
                  <div class="flex items-center text-sm text-gray-600 mb-2">
                    <Building class="w-4 h-4 mr-1" />
                    <span>{{ teammate.department }}</span>
                  </div>
                  <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                    <Calendar class="w-4 h-4 mr-1" />
                    {{ teammate.membership_period }}
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <p class="text-gray-700 text-sm mb-2 line-clamp-2">
                  {{ teammate.small_desc }}
                </p>
              </div>

              <!-- Social Links -->
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                  <a
                    v-if="teammate.facebook_link"
                    :href="teammate.facebook_link"
                    target="_blank"
                    class="p-2 rounded-lg bg-blue-50 text-blue-600"
                  >
                    <Facebook class="w-5 h-5" />
                  </a>
                  <a
                    v-if="teammate.linkedin_link"
                    :href="teammate.linkedin_link"
                    target="_blank"
                    class="p-2 rounded-lg bg-blue-50 text-blue-600"
                  >
                    <Linkedin class="w-5 h-5" />
                  </a>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center space-x-2">
                <Link
                  :href="route('team.edit', teammate.id)"
                  class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300"
                >
                  <Pencil class="w-4 h-4" />
                  Edit
                </Link>
                <button
                  @click="askToDelete(teammate.id)"
                  class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300"
                >
                  <Trash class="w-4 h-4" />
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="teammates.length === 0" class="py-16 px-4 text-center">
          <div class="mx-auto w-24 h-24 bg-gradient-to-br from-teal-50 to-teal-100 rounded-full flex items-center justify-center mb-6 border-2 border-teal-200">
            <User class="w-12 h-12 text-teal-400" />
          </div>
          <h3 class="text-xl font-semibold text-gray-800 mb-3">No teammates found</h3>
          <p class="text-gray-600 max-w-md mx-auto mb-8">
            Start building your team by adding the first teammate. Add their details, profile picture, and professional information.
          </p>
          <Link
            :href="route('team.create')"
            class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-xl px-8 py-3.5 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
          >
            <Plus class="w-5 h-5" />
            Add Your First Teammate
          </Link>
        </div>

        <!-- Stats Footer -->
        <div v-if="teammates.length > 0" class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
          <div class="flex flex-col lg:flex-row justify-between items-center">
            <div class="text-sm text-gray-600 mb-2 lg:mb-0">
              Showing <span class="font-semibold text-teal-700">{{ teammates.length }}</span> 
              teammate{{ teammates.length !== 1 ? 's' : '' }}
            </div>
            <div class="text-sm text-gray-600">
              Last updated: {{ getDate(new Date()) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}

.line-clamp-3 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
}

.hover-lift:hover {
  transform: translateY(-2px);
}
</style>