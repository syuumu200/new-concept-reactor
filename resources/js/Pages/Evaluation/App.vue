<template>
  <AppLayout>
    <Back :href="$route('projects.show', project.id)" />
    <h1>意見を評価する</h1>
    <div class="grid md:grid-cols-2 gap-3">
      <div v-if="material" class="card">
        <p class="whitespace-pre-line">{{ material.body }}</p>
        <div class="flex justify-evenly">
          <button class="link" @click="send(+1)">良い</button>
          <button class="link" @click="send(0)">どちらでもない</button>
          <button class="link" @click="send(-1)">悪い</button>
        </div>
      </div>
      <p v-else class="text-center">意見の評価を全て終えました。</p>
      <section class="flex flex-col gap-3">
        <div v-if="material === null" class="card text-center">
          <p>もし評価を後から変更する場合には，下記のボタンを押してください。</p>
          <Link class="link" method="delete" :data="{ project: project }" :href="$route('evaluations.reset')">評価をやり直す
          </Link>
        </div>
        <div v-if="project.evaluation_percentage > project.reflection_start" class="card text-center">
          <p>「意見の振り返り」が解禁されました。</p>
          <Link class="link" :data="{ project_id: project.id }" :href="$route('reflection')">意見を振り返る</Link>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Back from "@/Assets/Back.vue";
import { router } from '@inertiajs/vue3'

export default defineComponent({
  components: {
    AppLayout,
    Back
  },
  props: {
    project: Object,
    material: Object
  },
  created: function () {
  },
  methods: {
    send(value) {
      router.post(this.$route('evaluations.store'), {
        project_id: this.project.id,
        material: this.material,
        value: value
      })
    }
  },
});
</script>
