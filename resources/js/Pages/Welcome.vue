<template>
  <div class="h-full w-full absolute flex justify-center items-center">
    <div class="text-center">
      <h1 class="block text-3xl md:text-7xl">New ConceptReactor</h1>
      <Link class="link text-center" :href="$route('projects.index')">プロジェクトの一覧</Link>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  components: {
    AppLayout,
  },
});
</script>
