<template>
  <AppLayout>
    <Back :href="$route('projects.index')" />
    <form @submit.prevent="submit">
      <label>プロジェクトの名前</label>
      <input placeholder="プロジェクトの名前を入力してください" v-model="form.name" required />
      <Error errorKey="name" />
      <label>プロジェクト概要</label>
      <textarea placeholder="プロジェクトの概要を入力してください" rows="10" v-model="form.description" required />
      <Error errorKey="description" />
      <label>プロジェクトで集めるもの</label>
      <input class="mb-0" placeholder="プロジェクトで集めるものを入力してください" v-model="form.collectibles" required />
      <Error errorKey="collectibles" />
      <label>プロジェクトのゴール</label>
      <input class="mb-0" placeholder="プロジェクトのゴール" v-model="form.goal" required />
      <Error errorKey="goal" />
      <h2>ファシリテーター</h2>
      <label>名前</label>
      <input class="mb-0" placeholder="ファシリテーターの名前を入力してください" v-model="form.facilitator.name" required />
      <Error errorKey="facilitator.name" />
      <label>一人称</label>
      <input class="mb-0" placeholder="ファシリテーターの一人称を入力してください" v-model="form.facilitator.firstPerson" required />
      <Error errorKey="facilitator.firstPerson" />
      <label>敬称</label>
      <input class="mb-0" placeholder="ファシリテーターがユーザーを呼ぶ時の敬称を入力してください" v-model="form.facilitator.honorificTitle"
        required />
      <Error errorKey="facilitator.honorificTitle" />
      <label>性格</label>
      <input class="mb-0" placeholder="ファシリテーターの性格を入力してください" v-model="form.facilitator.character" required />
      <Error errorKey="facilitator.character" />
      <label>語尾</label>
      <input class="mb-0" placeholder="ファシリテーターの語尾を入力してください" v-model="form.facilitator.endOfSentence" required />
      <Error errorKey="facilitator.endOfSentence" />
      <label>口癖</label>
      <input class="mb-0" placeholder="ファシリテーターの口癖を入力してください" v-model="form.facilitator.favouritePhrase" required />
      <Error errorKey="facilitator.favouritePhrase" />
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
      <button type="submit" class="btn mt-3 block w-full">プロジェクトを作成する</button>
    </form>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from '@inertiajs/vue3'
import Back from "@/Assets/Back.vue";
import Error from "@/Assets/Error.vue";

export default defineComponent({
  data() {
    return {
      form: {
        name: '明日のごはんについて考えるプロジェクト',
        description: "明日のごはんを考えるためのプロジェクトです。",
        collectibles: '料理',
        goal: '明日のごはんを決定する',
        facilitator: {
          name: 'NewConceptReactorファシリテーションAI',
          firstPerson: '私',
          honorificTitle: 'さん',
          endOfSentence: '無し',
          character: '一般的',
          favouritePhrase: '無し'
        },
        cross_start: 5,
        vote_start: 10,
        reflection_start: 70,
      },
    }
  },
  components: {
    AppLayout,
    Back,
    Error
  },
  methods: {
    submit() {
      router.post('/projects', this.form)
    },
  },
});
</script>
