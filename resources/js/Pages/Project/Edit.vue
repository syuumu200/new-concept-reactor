<template>
  <AppLayout>
    <Back :href="$route('projects.show', project)" />
    <h1>プロジェクトの編集</h1>
    <form @submit.prevent="form.put($route('projects.update', project))">
      <h2>プロジェクト</h2>
      <label>プロジェクトの名前</label>
      <input placeholder="プロジェクトの名前を入力してください" v-model="form.name" required />
      <Error errorKey="name" />
      <label>プロジェクト概要</label>
      <textarea placeholder="プロジェクトの概要を入力してください" rows="10" v-model="form.description" required />
      <Error errorKey="description" />
      <h2>ファシリテーター</h2>
      <label>ファシリテーター設定</label>
      <textarea placeholder="ファシリテーターの設定を入力してください。" rows="10" v-model="form.facilitator" required />
      <Error errorKey="facilitator" />
      <h2>進展条件</h2>
      <label>交差開始意見数</label>
      <input type="number" class="mb-0" placeholder="交差開始意見数" v-model="form.cross_start" required />
      <Error errorKey="cross_start" />
      <label>評価開始意見数</label>
      <input type="number" class="mb-0" :min="form.cross_start" placeholder="投票開始意見数" v-model="form.vote_start"
        required />
      <Error errorKey="vote_start" />
      <label>振り返り開始評価率</label>
      <input type="number" class="mb-0" min="50" max="100" step="5" placeholder="振り返り開始評価率"
        v-model="form.reflection_start" required />
      <Error errorKey="reflection_start" />
      <button type="submit" class="btn mt-3 block w-full">プロジェクトを更新する</button>
    </form>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import { useForm } from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue";
import Back from "@/Assets/Back.vue";
import Error from "@/Assets/Error.vue";

export default defineComponent({
  data() {
    return {
      form: useForm({
        name: '',
        description: "",
        facilitator: '',
        cross_start: 5,
        vote_start: 10,
        reflection_start: 70,
      }),
    }
  },
  props: {
    project: Object
  },
  created: function () {
    this.form.name = this.project.name
    this.form.description = this.project.description
    this.form.facilitator = this.project.facilitator
    this.form.cross_start = this.project.cross_start
    this.form.vote_start = this.project.vote_start
    this.form.reflection_start = this.project.reflection_start
  },
  components: {
    AppLayout,
    Back,
    Error
  },
});
</script>
