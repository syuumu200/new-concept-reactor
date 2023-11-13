<template>
  <AppLayout>
    <div class="grid grid-cols-1 divide-y gap-3">
      <Link as="article" class="cursor-pointer" :href="$route('projects.show', project)" v-for="(project, key) in projects"
        :key="key">
      <div class="flex justify-between items-center">
        <h1 class="font-bold">{{ project.name }}</h1>
        <span>{{ project.user.username }}</span>
      </div>
      <p class="whitespace-pre-line">{{ project.description }}</p>
      </Link>
    </div>
    <CreateButton :href="$route('projects.create')" />
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CreateButton from "@/Assets/CreateButton.vue";

export default defineComponent({
  components: {
    AppLayout,
    CreateButton
  },
  props: {
    projects: Array
  }
});
</script>
