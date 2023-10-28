<template>
  <AppLayout>
    <p>{{ project.user.username }}の</p>
    <h1 class="text-2xl font-bold">{{ project.name }}</h1>
    <p>{{ project.description }}</p>
    <Link class="link" :href="$route('materials.create', { project_id: project.id })">プロジェクトに参加する</Link>
    <section v-if="project.materials_count > project.convergence_start">
      <h2 class=" font-semibold">登録された意見</h2>
      <div class="flex flex-col gap-3">
        <article class="card" v-for="(material, key) in project.materials" :key="key">
          <small>{{ material.user.username }}</small>
          <p class="whitespace-pre-line">{{ material.body }}</p>
        </article>
      </div>
    </section>
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
    project: Object
  }
});
</script>
