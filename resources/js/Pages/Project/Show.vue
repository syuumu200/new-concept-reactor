<template>
  <AppLayout>
    <Back :href="$route('projects.index')" />
    <div class="flex justify-between flex-wrap items-baseline border-b-4 mb-2">
      <h1>{{ project.name }}</h1>
      <Link v-if="project.user.id === $page.props.auth.user.id" :href="$route('projects.edit', project)"
        class="material-symbols-outlined">edit_note </Link>
      <button v-if="project.user.id === $page.props.auth.user.id" class="material-symbols-outlined"
        @click="destroy()">delete</button>
      <span class="grow text-right">{{ project.user.username }}</span>
    </div>
    <section class="grid md:grid-cols-9 gap-2">
      <div class="md:col-span-2 flex flex-col gap-3">
        <button class="btn" @click="moveMaterialCreate()" :disabled="isLoading">
          1. 意見を登録する
        </button>
        <Link class="btn" as="button" :href="$route('evaluations.create', { project_id: project.id })"
          :disabled="project.vote_start > project.materials_count">2. 意見を評価する
        </Link>
        <Link class="btn" as="button" :href="$route('reflection', { project_id: project.id })"
          :disabled="project.reflection_start > project.evaluation_percentage">
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
        <p>評価率　{{ project.evaluation_percentage }}%</p>
      </div>
    </section>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Back from "@/Assets/Back.vue";
import { router } from '@inertiajs/vue3'

export default defineComponent({
  data() {
    return {
      isLoading: false
    }
  },
  components: {
    AppLayout,
    Back
  },
  props: {
    project: Object
  },
  methods: {
    moveMaterialCreate: function () {
      router.get(this.$route('materials.create'), {
        project_id: this.project.id
      },
        {
          onStart: () => this.isLoading = true,
          onFinish: () => this.isLoading = false
        })
    },
    destroy: function () {
      router.delete(this.$route('projects.destroy', this.project), {
        onBefore: () => confirm('本当に削除していいですか？')
      })
    }
  }
});
</script>
