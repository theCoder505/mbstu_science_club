<script setup lang="ts">
import Heading from "@/components/Heading.vue";
import { Separator } from "@/components/ui/separator";
</script>

<template>
  <div class="px-4 py-6">
    <Heading title="Settings" description="Manage your profile and account settings" />

    <div class="">
      <Separator class="my-6 lg:hidden" />
      <slot />
    </div>
  </div>
</template>
