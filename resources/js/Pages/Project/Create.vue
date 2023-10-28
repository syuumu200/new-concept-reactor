<template>
  <AppLayout>
    <form @submit.prevent="submit">
      <input placeholder="プロジェクトの名前を入力してください" v-model="form.name" />
      <textarea placeholder="プロジェクトの概要を入力してください" v-model="form.description" />
      <p>交差開始意見数</p>
      <input class="mb-0" placeholder="交差開始意見数" v-model="form.cross_start" />
      <small>※プロジェクト内の意見数がここで指定した数字を超えると意見の交差を開始します。</small>
      <p>整理開始意見数</p>
      <input class="mb-0" placeholder="整理開始意見数" v-model="form.convergence_start" />
      <small>※プロジェクト内の意見数がここで指定した数字を超えると意見の整理を開始します。</small>
      <button type="submit" class="btn mt-3 block w-full">プロジェクトを作成する</button>
    </form>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from '@inertiajs/vue3'

export default defineComponent({
  data() {
    return {
      form: {
        name: '明日のごはんについて考えるプロジェクト',
        description: '明日のごはんを考えるためのプロジェクトです。皆さんからはごはんのアイデアを募ります。',
        cross_start: 5,
        convergence_start: 10,
      },
    }
  },
  components: {
    AppLayout,
  },
  methods: {
    submit() {
      router.post('/projects', this.form)
    },
  },
});
</script>
