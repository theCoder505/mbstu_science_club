<script setup lang="ts">
defineProps({
  paginator: {
    type: Object,
    required: true,
  },
});

</script>

<template>
  <!-- Pagination Links -->
  <div class="mx-auto mt-4 grid gap-2 lg:flex justify-center lg:justify-between items-center flex-row-reverse">
        <p class="text-teal-800 text-sm text-center lg:text-right">
      Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} results
    </p>
    <div class="flex items-center shadow">
      <template v-for="href_link in paginator.links" :key="href_link.label">
        <component
          :is="href_link.url ? 'Link' : 'span'"
          preserve-scroll
          v-if="href_link.url"
          :href="href_link.url"
          class="border-x border-teal-50 min-w-12 h-12 p-2 grid place-items-center bg-white"
          :class="{
            'hover:bg-teal-500 hover:text-white hover:rounded-lg': href_link.url,
            'font-bold text-blue-500': href_link.active,
          }"
        >
          <span v-html="href_link.label"></span>
        </component>
        <span
          v-else
          v-html="href_link.label"
          class="p-1 mx-1 text-zinc-400 cursor-not-allowed"
        ></span>
      </template>
    </div>
  </div>
</template>
