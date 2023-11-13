<template>
  <AppLayout>
    <Back :href="$route('projects.index')" />
    <div class="flex justify-between flex-wrap items-baseline">
      <h1 class="text-2xl font-bold">{{ project.name }}</h1>
      <span>{{ project.user.username }}</span>
    </div>
    <section class="grid md:grid-cols-2 gap-2">
      <div>
        <h2>プロジェクトの概要</h2>
        <p class="whitespace-pre-line">{{ project.description }}</p>
        <h2>ファシリテーター設定</h2>
        <p class="whitespace-pre-line">{{ project.facilitator }}</p>
      </div>
      <div>
        <h2>進展条件</h2>
        <p>交差開始意見数　{{ project.cross_start }}</p>
        <p>投票開始意見数　{{ project.vote_start }}</p>
        <p>振り返り開始意見数　{{ project.reflection_start }}</p>
        <h2>状況</h2>
        <p>登録された意見　{{ project.materials_count }}</p>
      </div>
    </section>
    <div class="card flex flex-col md:flex-row justify-around my-3">
      <Link class="link" :href="$route('materials.create', { project_id: project.id })">1. 意見を登録する</Link>
      <Link class="link" :href="$route('evaluation', { project_id: project.id })">2. 意見を評価する</Link>
      <Link class="link" :href="$route('reflection', { project_id: project.id })">3. 意見を振り返る</Link>
    </div>
    <section v-if="project.materials_count > project.convergence_start">
      <h2>登録された意見</h2>
      <div class="grid grid-cols-1 divide-y gap-3">
        <article v-for="(material, key) in project.materials" :key="key">
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
import Back from "@/Assets/Back.vue";

export default defineComponent({
  components: {
    AppLayout,
    Back
  },
  props: {
    project: Object
  }
});
</script>
