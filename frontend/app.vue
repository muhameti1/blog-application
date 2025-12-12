<template>
  <div>
    <NuxtPage />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from "vue";

const { initAuth } = useAuth();

// Initialize auth state on app mount
onMounted(() => {
  initAuth();
});
</script>
