<template>
  <AppLayout>
    <form @submit.prevent="submit">
      <label>プロジェクトの名前</label>
      <input placeholder="プロジェクトの名前を入力してください" v-model="form.name" />
      <label>プロジェクト概要</label>
      <textarea placeholder="プロジェクトの概要を入力してください" rows="10" v-model="form.description" />
      <label>プロジェクトで集めるもの</label>
      <input class="mb-0" placeholder="プロジェクトであつめるものを入力してください" v-model="form.collectibles" />
      <h2>ファシリテーター</h2>
      <label>名前</label>
      <input class="mb-0" placeholder="ファシリテーターの名前を入力してください" v-model="form.facilitator.name" />
      <label>一人称</label>
      <input class="mb-0" placeholder="ファシリテーターの一人称を入力してください" v-model="form.facilitator.firstPerson" />
      <label>語尾</label>
      <input class="mb-0" placeholder="ファシリテーターの語尾を入力してください" v-model="form.facilitator.endOfSentence" />
      <label>性格</label>
      <input class="mb-0" placeholder="ファシリテーターの性格を入力してください" v-model="form.facilitator.character" />
      <h2>進展条件</h2>
      <p>交差開始意見数</p>
      <input class="mb-0" placeholder="交差開始意見数" v-model="form.cross_start" />
      <small>※プロジェクト内の意見数がここで指定した数字に達すると意見の交差を開始します。</small>
      <p>投票開始意見数</p>
      <input class="mb-0" placeholder="投票開始意見数" v-model="form.vote_start" />
      <small>※プロジェクト内の意見数がここで指定した数字に達すると意見への投票を開始します。</small>
      <p>振り返り開始意見数</p>
      <input class="mb-0" placeholder="振り返り開始意見数" v-model="form.reflection_start" />
      <small>※プロジェクト内の意見数がここで指定した数字に達するとプロジェクトで登録された意見を振り返り，総括します。</small>
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
        description: "明日のごはんを考えるためのプロジェクトです。",
        collectibles: '料理',
        facilitator: {
          name: 'NewConceptReactorファシリテーションAI',
          firstPerson: '私',
          endOfSentence:'無し',
          character:'一般的'
        },
        cross_start: 5,
        vote_start: 10,
        reflection_start: 15,
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
