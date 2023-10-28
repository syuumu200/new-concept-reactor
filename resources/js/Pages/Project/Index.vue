<template>
  <AppLayout>
    <div class="flex flex-col gap-3">
      <Link as="article" :href="$route('projects.show', project)" class="card" v-for="(project, key) in projects"
        :key="key">
      <div class="flex justify-between">
        <h1>{{ project.name }}</h1>
        <span>{{ project.user.username }}</span>
      </div>
      <p>{{ project.description }}</p>
      </Link>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  components: {
    AppLayout,
  },
  props: {
    projects: Array
  }
});
</script>
