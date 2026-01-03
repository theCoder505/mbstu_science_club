<script setup lang="ts">
const model = defineModel({
  type: null,
  required: true,
});

defineProps({
  placeholder: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    default: "text",
  },
  message: String,
});
</script>

<template>
  <div class="w-full">
    <input
      :type="type"
      v-model="model"
      :placeholder="placeholder"
      class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 text-teal-600 placeholder-teal-400 bg-teal-50"
      :class="message ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-teal-300 focus:ring-teal-500 focus:border-teal-400'"
    />
    <small v-if="message" class="text-red-500 font-medium text-sm mt-2 block">{{ message }}</small>
  </div>
</template>