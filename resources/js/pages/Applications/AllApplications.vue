<script setup lang="ts">
import PaginationLinks from "@/components/PaginationLinks.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import {
  Activity,
  Briefcase,
  Calendar,
  Clock,
  Mail,
  Pencil,
  Settings,
  Trash,
  User,
} from "lucide-vue-next";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Applications",
    href: "/validate-application",
  },
];

const props = defineProps({
  applications: {
    type: Object,
    default: () => ({}),
  },
  searchTerm: String,
});

const getDate = (date: any) => {
  return new Date(date).toLocaleDateString("en-uk", {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  });
};

const askToDelete = (query_id:any) => {
  if (confirm("Are you sure you want to delete this application?")) {
    router.post("/application/delete", {itemID: query_id}, {preserveScroll: true})
  }
};

const search = ref(props.searchTerm);

watch(
  search,
  debounce(
    (query) => router.get("/applications", { search: query }, { preserveState: true }),
    100
  )
);
</script>

<template>
  <Head title="All Certificate Applications" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div>
      <h1 class="text-center text-2xl my-6 font-bold capitalize">
        All Certificate Applications
      </h1>
    </div>

    <div class="mx-4 w-full lg:w-11/12 overflow-x-auto lg:mx-auto mb-12">
      <div class="flex lg:justify-end mb-4 mx-auto">
        <input
          type="search"
          placeholder="Search..."
          v-model="search"
          class="border px-4 py-2 rounded border-teal-600 outline-0 w-[350px]"
        />
      </div>
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-max">
            <thead class="bg-gradient-to-r from-teal-500 to-teal-600">
              <tr>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <User class="w-4 h-4" />
                    <span>Applicant</span>
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
                    <span>Designation</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Calendar class="w-4 h-4" />
                    <span>Period</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Activity class="w-4 h-4" />
                    <span>Status</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Settings class="w-4 h-4" />
                    <span>Actions</span>
                  </div>
                </th>
                <th class="py-4 px-6 text-left font-semibold text-white tracking-wider">
                  <div class="flex items-center space-x-2">
                    <Clock class="w-4 h-4" />
                    <span>Applied At</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
              <tr
                v-for="(application, index) in (applications?.data as any[])"
                :key="application.id"
                :class="[
                  'hover:bg-teal-100 transition-colors duration-150 border-b-2 border-gray-200',
                  (index as number) % 2 == 0 ? 'bg-white' : 'bg-gray-50',
                ]"
              >
                <!-- Applicant -->
                <td class="py-4 px-6">
                  <div>
                    <div class="font-medium text-gray-900 capitalize">
                      {{ application.applicant_name }}
                    </div>
                  </div>
                </td>

                <!-- Email -->
                <td class="py-4 px-6">
                  <a
                    :href="'mailto:' + application.email"
                    class="group flex items-center text-blue-600 hover:text-blue-800 transition-colors"
                  >
                    <span class="truncate max-w-[180px]">{{ application.email }}</span>
                  </a>
                </td>

                <!-- Designation -->
                <td class="py-4 px-6">
                  <div class="flex items-center justify-center">
                    <span
                      class="bg-gradient-to-r from-teal-100 to-teal-50 text-teal-700 text-sm font-medium px-3 py-1.5 rounded-full border border-teal-200"
                    >
                      {{ application.designation }}
                    </span>
                  </div>
                </td>

                <!-- Period -->
                <td class="py-4 px-6">
                  <div class="flex flex-col items-start">
                    <div class="flex items-center text-sm text-gray-600 text-center">
                      {{ getDate(application.member_since) }}
                      <br />
                      to
                      <br />
                      {{ getDate(application.member_till) }}
                    </div>
                  </div>
                </td>

                <!-- Status -->
                <td class="py-4 px-6">
                  <div class="inline-flex items-center">
                    <span class="relative flex h-2.5 w-2.5 mr-2">
                      <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                        :class="{
                          'bg-green-400': application.certificate_status === 'approved',
                          'bg-blue-400': application.certificate_status === 'verified',
                          'bg-yellow-400': application.certificate_status === 'pending',
                          'bg-orange-400': application.certificate_status === 'revision',
                          'bg-red-400': application.certificate_status === 'declined',
                        }"
                      ></span>
                      <span
                        class="relative inline-flex rounded-full h-2.5 w-2.5"
                        :class="{
                          'bg-green-500': application.certificate_status === 'approved',
                          'bg-blue-500': application.certificate_status === 'verified',
                          'bg-yellow-500': application.certificate_status === 'pending',
                          'bg-orange-500': application.certificate_status === 'revision',
                          'bg-red-500': application.certificate_status === 'declined',
                        }"
                      ></span>
                    </span>
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-[0.6rem] font-semibold tracking-wide uppercase"
                      :class="{
                        'bg-green-100 text-green-800 border border-green-200':
                          application.certificate_status === 'approved',
                        'bg-blue-100 text-blue-800 border border-blue-200':
                          application.certificate_status === 'verified',
                        'bg-yellow-100 text-yellow-800 border border-yellow-200':
                          application.certificate_status === 'pending',
                        'bg-orange-100 text-orange-800 border border-orange-200':
                          application.certificate_status === 'revision',
                        'bg-red-100 text-red-800 border border-red-200':
                          application.certificate_status === 'declined',
                      }"
                    >
                      {{ application.certificate_status }}
                    </span>
                  </div>
                </td>

                <!-- Actions -->
                <td class="py-4 px-6">
                  <div class="flex items-center justify-center space-x-2">
                    <Link
                      :href="'/applications/' + application.id + '/' + application.applicant_name"
                      class="p-2 rounded-lg hover:bg-green-50 text-green-600 hover:text-green-700 transition-colors group"
                      title="Edit"
                    >
                      <Pencil
                        class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer"
                      />
                    </Link>
                    <button
                      class="p-2 rounded-lg hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors group"
                      title="Delete"
                      @click="askToDelete(application.id)"
                    >
                      <Trash
                        class="w-4 h-4 group-hover:scale-110 transition-transform cursor-pointer"
                      />
                    </button>
                  </div>
                </td>

                <!-- Applied At -->
                <td class="py-4 px-6">
                  <div class="text-right">
                    <div class="text-sm font-medium text-gray-700">
                      {{ getDate(application.created_at) }}
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination Links -->
      <PaginationLinks :paginator="applications" />
    </div>
  </AppLayout>
</template>
