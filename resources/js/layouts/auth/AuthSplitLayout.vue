<script setup lang="ts">
defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <div
    class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 bg-yellow-400"
  >
    <div
      class="relative hidden h-full flex-col bg-muted text-white lg:flex dark:border-r"
    >
      <img
        src="/assets/images/fiction.jpg"
        alt="MBSTUSC"
        class="h-[100vh] object-cover"
      />
    </div>
    <div class="max-w-[350px] bg-slate-50 p-4 lg:p-6 shadow-xl rounded-2xl mx-auto">
      <div class="mx-auto flex w-full flex-col justify-center space-y-6">
        <div class="flex flex-col space-y-2 text-center">
          <h1 class="text-xl font-medium tracking-tight" v-if="title">
            {{ title }}
          </h1>
          <p class="text-sm text-muted-foreground" v-if="description">
            {{ description }}
          </p>
        </div>
        <slot />
      </div>
    </div>
  </div>
</template>
