<template>
  <AppLayout>
    <Back label="ダッシュボードに戻る" :href="$route('projects.show', project.id)" />

    <section class="grid grid-cols-1 md:grid-cols-2 gap-2">
      <div>
        <div class="flex justify-between items-center gap-2">
          <button class="btn flex-none" v-if="page !== 1" @click="page--">
            <span class="material-symbols-outlined">
              arrow_back_ios_new
            </span>
          </button>
          <button class="btn flex-none" v-if="page !== messages.length" @click="page++">
            <span class="material-symbols-outlined">
              arrow_forward_ios
            </span>
          </button>
          <div class="grow text-right">
            <button class="btn" @click="forget()">リセット</button>
          </div>
        </div>
        <p class="whitespace-pre-line">{{ message.content }}</p>
      </div>
      <div>
        <form v-if="!form.processing" @submit.prevent="submit">
          <div class=" text-right">
            <button class="btn" @click.prevent="form.targets.unshift({ body: '' })">入力枠を追加する</button>
          </div>
          <div v-for="(material, key) in form.targets" :key="key">
            <textarea class="mb-0" placeholder="指示に従って入力してください。" rows="3" v-model="material.body" />
            <div class=" text-right">
              <button v-if="form.targets.length > 1" class="btn mt-0 rounded-none rounded-br-md rounded-bl-md text-sm"
                @click.prevent="form.targets.splice(key, 1)">削除する</button>
            </div>
          </div>
          <button type="submit" class="btn block w-full mt-3">登録する</button>
        </form>
        <p v-else class="animate-bounce text-center">
          送信中
        </p>
      </div>
    </section>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from '@inertiajs/vue3'
import Back from "@/Assets/Back.vue";

export default defineComponent({
  data() {
    return {
      page: 1,
      form: useForm({
        project_id: '',
        suggestion: '',
        sources: [],
        targets: [
          {
            body: ''
          }
        ],
      }),
    }
  },
  components: {
    AppLayout,
    Back
  },
  props: {
    sources: Array,
    project: Object,
    messages: Array
  },
  created: function () {
    this.showLastMessage()
  },
  computed: {
    message: function () {
      return this.messages[this.page - 1]
    }
  },
  methods: {
    showLastMessage: function () {
      this.page = this.messages.length
    },
    forget: function () {
      router.delete(this.$route('materials.forget', {
        project_id: this.project.id
      }), {
        onBefore: function () {
          return confirm('会話をリセットして最初からやり直します。')
        }.bind(this),
        onStart: function () {
          this.page = 1
        }.bind(this)
      })
    },
    submit: function () {
      this.form.project_id = this.project.id
      this.form.suggestion = this.messages[this.messages.length - 1].content
      this.form.sources = this.sources
      this.form.post('/materials', {
        onSuccess: function () {
          this.form.targets = [{ body: '' }]
          this.showLastMessage()
        }.bind(this)
      })
    },
  },
});
</script>
