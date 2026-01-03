<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import { Plus, Pencil, Trash, User, Mail, Briefcase, Settings, Calendar, Award } from "lucide-vue-next";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Advisor & Moderators",
    href: "/advisors-and-moderators",
  },
];

const props = defineProps({
  advisors: {
    type: Array,
    default: () => [],
  },
  searchTerm: String,
});

const getDate = (date: any) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-uk", {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  });
};

const askToDelete = (id: any) => {
  if (confirm("Are you sure you want to delete this advisor/moderator?")) {
    router.delete(`/advisors-and-moderators/${id}`, { preserveScroll: true });
  }
};

const search = ref(props.searchTerm);

watch(
  search,
  debounce(
    (query) => router.get("/advisors-and-moderators", { search: query }, { preserveState: true }),
    300
  )
);
</script>

<template>
  <Head title="All Advisor & Moderators" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 lg:px-8 my-6">
      <div>
        <h1 class="text-center text-2xl my-6 font-bold capitalize">
          Advisor & Moderators
        </h1>
      </div>

      <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
        <Link
          href="/advisors-and-moderators/create"
          class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl text-white px-4 py-2.5 flex items-center gap-2 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
          <Plus class="w-5 h-5" />
          Create New
        </Link>
        
        <div class="flex w-full lg:w-auto">
          <input
            type="search"
            placeholder="Search by name, email, or department..."
            v-model="search"
            class="border px-4 py-2.5 rounded-xl border-teal-600 outline-0 w-full lg:w-[350px] focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
          />
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
          <table class="w-full min-w-max">
            <thead class="bg-gradient-to-r from-teal-500 to-teal-600">
              <tr>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <User class="w-4 h-4" />
                    <span>Name</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Mail class="w-4 h-4" />
                    <span>Email</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Briefcase class="w-4 h-4" />
                    <span>Department</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Award class="w-4 h-4" />
                    <span>Role</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Calendar class="w-4 h-4" />
                    <span>Joined At</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Settings class="w-4 h-4" />
                    <span>Actions</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
              <tr
                v-for="(advisor, index) in (advisors as any[])"
                :key="advisor.id"
                :class="[
                  'hover:bg-teal-50 transition-colors duration-150 border-b-2 border-gray-200',
                  index % 2 === 0 ? 'bg-white' : 'bg-gray-50',
                ]"
              >
                <!-- Name -->
                <td class="py-4 px-6">
                  <div class="flex items-center space-x-3">
                    <div v-if="advisor.profile_image" class="flex-shrink-0">
                      <img
                        :src="`/storage/${advisor.profile_image}`"
                        :alt="advisor.advisor_name"
                        class="w-10 h-10 rounded-full object-cover border-2 border-teal-200"
                      />
                    </div>
                    <div v-else class="flex-shrink-0">
                      <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center border-2 border-teal-200">
                        <User class="w-5 h-5 text-teal-600" />
                      </div>
                    </div>
                    <div class="max-w-[250px]">
                      <div class="font-medium text-gray-900 capitalize">
                        {{ advisor.advisor_name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ advisor.designation }}
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Email -->
                <td class="py-4 px-6">
                  <a
                    :href="'mailto:' + advisor.email"
                    class="group flex items-center text-teal-600 hover:text-teal-800 transition-colors"
                  >
                    <span class="truncate max-w-[180px]">{{ advisor.email }}</span>
                  </a>
                </td>

                <!-- Department -->
                <td class="py-4 px-6">
                  <div class="flex items-center">
                    <span
                      class="bg-gradient-to-r from-teal-100 to-teal-50 text-teal-700 text-sm font-medium px-3 py-1.5 rounded-full border border-teal-200"
                    >
                      {{ advisor.department }}
                    </span>
                  </div>
                </td>

                <!-- Role -->
                <td class="py-4 px-6">
                  <div class="inline-flex items-center">
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold tracking-wide uppercase"
                      :class="{
                        'bg-purple-100 text-purple-800 border border-purple-200': advisor.club_role === 'Advisor',
                        'bg-indigo-100 text-indigo-800 border border-indigo-200': advisor.club_role === 'Moderator',
                      }"
                    >
                      {{ advisor.club_role }}
                    </span>
                  </div>
                </td>

                <!-- Joined At -->
                <td class="py-4 px-6">
                  <div class="text-sm font-medium text-gray-700">
                    {{ getDate(advisor.created_at) }}
                  </div>
                </td>

                <!-- Actions -->
                <td class="py-4 px-6">
                  <div class="flex items-center justify-center space-x-2">
                    <Link
                      :href="'/advisors-and-moderators/' + advisor.id + '/edit'"
                      class="p-2 rounded-lg hover:bg-green-50 text-green-600 hover:text-green-700 transition-colors group"
                      title="Edit"
                    >
                      <Pencil class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer" />
                    </Link>
                    <button
                      class="p-2 rounded-lg hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors group"
                      title="Delete"
                      @click="askToDelete(advisor.id)"
                    >
                      <Trash class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Empty State -->
        <div v-if="advisors.length === 0" class="py-12 text-center">
          <div class="mx-auto w-24 h-24 bg-teal-50 rounded-full flex items-center justify-center mb-4">
            <User class="w-12 h-12 text-teal-400" />
          </div>
          <h3 class="text-lg font-semibold text-gray-700 mb-2">No advisors found</h3>
          <p class="text-gray-500 mb-6">Get started by creating a new advisor or moderator.</p>
          <Link
            href="/advisors-and-moderators/create"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl text-white px-6 py-2.5 font-bold hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl"
          >
            <Plus class="w-5 h-5" />
            Create New Advisor
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>