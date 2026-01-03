<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface Advisor {
    id: number;
    advisor_name: string;
    email: string;
    department: string;
    designation: string;
    club_role: string;
    profile_image: string | null;
}

interface Applications {
    id: number;
    applicant_name: string;
    certificate_status: string;
}

interface Team {
    id: number;
    teammate_name: string;
    teammate_image: string | null;
    department: string;
    designation: string;
    membership_period: string;
}

interface Committee {
    id: number;
    committee_name: string;
    committee_period: string;
    committee_images: string[];
}

interface FAQ {
    id: number;
    question: string;
    answer: string;
}

interface Gallery {
    id: number;
    image_path: string;
    status: string;
    position: number;
}

interface Statistic {
    id: number;
    page_url: string;
    created_at: string;
}

interface Props {
    advisors: Advisor[];
    applications: Applications[];
    team: Team[];
    committees: Committee[];
    faqs: FAQ[];
    gallery: Gallery[];
    statistics: Statistic[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const active_chart = ref<'daily' | 'weekly' | 'monthly' | 'yearly' | 'all_time'>('weekly');

// Statistics calculations
const getStatsByPeriod = (period: 'daily' | 'weekly' | 'monthly' | 'yearly') => {
    const now = new Date();
    const stats: Record<string, number> = {};

    props.statistics.forEach((stat) => {
        const createdAt = new Date(stat.created_at);
        let isInPeriod = false;

        switch (period) {
            case 'daily':
                isInPeriod = createdAt.toDateString() === now.toDateString();
                break;
            case 'weekly':
                const weekAgo = new Date(now);
                weekAgo.setDate(now.getDate() - 7);
                isInPeriod = createdAt >= weekAgo;
                break;
            case 'monthly':
                isInPeriod =
                    createdAt.getMonth() === now.getMonth() &&
                    createdAt.getFullYear() === now.getFullYear();
                break;
            case 'yearly':
                isInPeriod = createdAt.getFullYear() === now.getFullYear();
                break;
        }

        if (isInPeriod) {
            stats[stat.page_url] = (stats[stat.page_url] || 0) + 1;
        }
    });

    return stats;
};

const dailyStats = computed(() => getStatsByPeriod('daily'));
const weeklyStats = computed(() => getStatsByPeriod('weekly'));
const monthlyStats = computed(() => getStatsByPeriod('monthly'));
const yearlyStats = computed(() => getStatsByPeriod('yearly'));

const totalStats = computed(() => {
    const stats: Record<string, number> = {};
    props.statistics.forEach((stat) => {
        stats[stat.page_url] = (stats[stat.page_url] || 0) + 1;
    });
    return stats;
});

// Chart data generators
const createChartData = (stats: Record<string, number>, color: string) => {
    return {
        labels: Object.keys(stats),
        datasets: [
            {
                label: 'Views',
                backgroundColor: color,
                borderRadius: 8,
                data: Object.values(stats),
            },
        ],
    };
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            cornerRadius: 8,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                precision: 0,
            },
            grid: {
                color: 'rgba(0, 0, 0, 0.05)',
            },
        },
        x: {
            grid: {
                display: false,
            },
        },
    },
};

const dailyChartData = computed(() => createChartData(dailyStats.value, '#3b82f6'));
const weeklyChartData = computed(() => createChartData(weeklyStats.value, '#10b981'));
const monthlyChartData = computed(() => createChartData(monthlyStats.value, '#8b5cf6'));
const yearlyChartData = computed(() => createChartData(yearlyStats.value, '#f59e0b'));
const totalChartData = computed(() => createChartData(totalStats.value, '#6b7280'));

// Current chart data based on active selection
const currentChartData = computed(() => {
    switch (active_chart.value) {
        case 'daily':
            return { data: dailyChartData.value, title: 'Daily Views', hasData: Object.keys(dailyStats.value).length > 0 };
        case 'weekly':
            return { data: weeklyChartData.value, title: 'Weekly Views', hasData: Object.keys(weeklyStats.value).length > 0 };
        case 'monthly':
            return { data: monthlyChartData.value, title: 'Monthly Views', hasData: Object.keys(monthlyStats.value).length > 0 };
        case 'yearly':
            return { data: yearlyChartData.value, title: 'Yearly Views', hasData: Object.keys(yearlyStats.value).length > 0 };
        case 'all_time':
            return { data: totalChartData.value, title: 'All Time Views', hasData: Object.keys(totalStats.value).length > 0 };
        default:
            return { data: weeklyChartData.value, title: 'Weekly Views', hasData: Object.keys(weeklyStats.value).length > 0 };
    }
});

const chartPeriods = [
    { value: 'daily', label: 'Daily', icon: '📅' },
    { value: 'weekly', label: 'Weekly', icon: '📊' },
    { value: 'monthly', label: 'Monthly', icon: '📈' },
    { value: 'yearly', label: 'Yearly', icon: '📉' },
    { value: 'all_time', label: 'All Time', icon: '🕒' },
];
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <!-- Summary Cards -->
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!-- Advisors Card -->
        <div
          class="rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-blue-900 dark:from-blue-900/20 dark:to-blue-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-blue-700 dark:text-blue-300">
                Total Advisors
              </h3>
              <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">
                {{ advisors.length }}
              </p>
            </div>
            <div class="rounded-full bg-blue-200 p-3 dark:bg-blue-800/50">
              <svg
                class="h-8 w-8 text-blue-600 dark:text-blue-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Team Members Card -->
        <div
          class="rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-green-900 dark:from-green-900/20 dark:to-green-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-green-700 dark:text-green-300">
                Team Members
              </h3>
              <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">
                {{ team.length }}
              </p>
            </div>
            <div class="rounded-full bg-green-200 p-3 dark:bg-green-800/50">
              <svg
                class="h-8 w-8 text-green-600 dark:text-green-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Committees Card -->
        <div
          class="rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-purple-900 dark:from-purple-900/20 dark:to-purple-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-purple-700 dark:text-purple-300">
                Total Committees
              </h3>
              <p class="mt-2 text-3xl font-bold text-purple-900 dark:text-purple-100">
                {{ committees.length }}
              </p>
            </div>
            <div class="rounded-full bg-purple-200 p-3 dark:bg-purple-800/50">
              <svg
                class="h-8 w-8 text-purple-600 dark:text-purple-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Applications Card -->
        <div
          class="rounded-xl border border-rose-200 bg-gradient-to-br from-rose-50 to-rose-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-rose-900 dark:from-rose-900/20 dark:to-rose-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-rose-700 dark:text-rose-300">
                Applications
              </h3>
              <p class="mt-2 text-3xl font-bold text-rose-900 dark:text-rose-100">
                {{ applications.length }}
              </p>
            </div>
            <div class="rounded-full bg-rose-200 p-3 dark:bg-rose-800/50">
              <svg
                class="h-8 w-8 text-rose-600 dark:text-rose-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Gallery Card -->
        <div
          class="rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-amber-900 dark:from-amber-900/20 dark:to-amber-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-amber-700 dark:text-amber-300">
                Gallery Images
              </h3>
              <p class="mt-2 text-3xl font-bold text-amber-900 dark:text-amber-100">
                {{ gallery.length }}
              </p>
            </div>
            <div class="rounded-full bg-amber-200 p-3 dark:bg-amber-800/50">
              <svg
                class="h-8 w-8 text-amber-600 dark:text-amber-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- FAQs Card -->
        <div
          class="rounded-xl border border-cyan-200 bg-gradient-to-br from-cyan-50 to-cyan-100 p-6 shadow-sm transition-all hover:shadow-md dark:border-cyan-900 dark:from-cyan-900/20 dark:to-cyan-800/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-cyan-700 dark:text-cyan-300">FAQs</h3>
              <p class="mt-2 text-3xl font-bold text-cyan-900 dark:text-cyan-100">
                {{ faqs.length }}
              </p>
            </div>
            <div class="rounded-full bg-cyan-200 p-3 dark:bg-cyan-800/50">
              <svg
                class="h-8 w-8 text-cyan-600 dark:text-cyan-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart Period Selector -->
      <div class="flex flex-wrap items-center gap-3">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
          Statistics Period:
        </label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="period in chartPeriods"
            :key="period.value"
            @click="active_chart = period.value as typeof active_chart"
            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all"
            :class="
              active_chart === period.value
                ? 'bg-blue-600 text-white shadow-md dark:bg-blue-500'
                : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'
            "
          >
            <span>{{ period.icon }}</span>
            <span>{{ period.label }}</span>
          </button>
        </div>
      </div>

      <!-- Statistics Chart -->
      <div
        class="rounded-xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border dark:bg-gray-800"
      >
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ currentChartData.title }}
          </h3>
          <span
            class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200"
          >
            {{
              Object.values(currentChartData.data.datasets[0].data).reduce(
                (a, b) => a + b,
                0
              )
            }}
            Total Views
          </span>
        </div>

        <div v-if="currentChartData.hasData" style="height: 400px">
          <Bar :data="currentChartData.data" :options="chartOptions" />
        </div>

        <div v-else class="flex h-[400px] items-center justify-center">
          <div class="text-center">
            <svg
              class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
              />
            </svg>
            <p class="mt-4 text-lg font-medium text-gray-600 dark:text-gray-400">
              No data available
            </p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
              No views recorded for this period
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
button {
  cursor: pointer;
}
</style>
