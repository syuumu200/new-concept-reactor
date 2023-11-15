<template>
  <AppLayout>
    <Back :href="$route('projects.index')" />
    <div class="flex justify-between flex-wrap items-baseline border-b-4 mb-2">
      <h1>{{ project.name }}</h1>
      <Link v-if="project.user.id === $page.props.auth.user.id" :href="$route('projects.edit', project)"
        class="material-symbols-outlined">edit_note </Link>
      <span class="grow text-right">{{ project.user.username }}</span>
    </div>
    <section class="grid md:grid-cols-9 gap-2">
      <div class="md:col-span-2 flex flex-col gap-3">
        <Link class="btn" as="button" :href="$route('materials.create', { project_id: project.id })">1.
        意見を登録する</Link>
        <Link class="btn" as="button" :href="$route('evaluations.create', { project_id: project.id })"
          :disabled="project.vote_start > project.materials_count">2. 意見を評価する
        </Link>
        <Link class="btn" as="button" :href="$route('reflection', { project_id: project.id })"
          :disabled="project.reflection_start > Math.round(project.evaluations_count / (project.users_count * project.materials_count) * 100)">
        3. 意見を振り返る
        </Link>
      </div>
      <div class=" md:col-span-4">
        <h2>プロジェクトの概要</h2>
        <p class="whitespace-pre-line">{{ project.description }}</p>
        <h2>ファシリテーター設定</h2>
        <p class="whitespace-pre-line">{{ project.facilitator }}</p>
      </div>
      <div class=" md:col-span-3">
        <h2>進展条件</h2>
        <p>交差開始意見数　{{ project.cross_start }}</p>
        <p>評価開始意見数　{{ project.vote_start }}</p>
        <p>振り返り開始評価率　{{ project.reflection_start }}%</p>
        <h2>状況</h2>
        <p>参加ユーザー数　{{ project.users_count }}</p>
        <p>登録された意見　{{ project.materials_count }}</p>
        <p>評価数　{{ project.evaluations_count }}</p>
        <p>評価率　{{ Math.round(project.evaluations_count / (project.users_count * project.materials_count) * 100) }}%</p>
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
