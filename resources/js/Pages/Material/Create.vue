<template>
  <AppLayout>
    <Back label="戻る" :href="$route('projects.show', project.id)" />

    <section class="grid grid-cols-1 md:grid-cols-2 gap-2">
      <div>
        <div class="flex justify-between items-center gap-2">
          <button class="btn" v-if="page !== 1" @click="page--" :disabled="isReseting">
            <span class="material-symbols-outlined text-white">
              arrow_back_ios_new
            </span>
          </button>
          <button class="btn" v-if="page !== messages.length" @click="page++" :disabled="isReseting">
            <span class="material-symbols-outlined text-white">
              arrow_forward_ios
            </span>
          </button>
          <div class="grow text-right">
            <button class="btn" @click="forget()" :disabled="isReseting">リセット</button>
          </div>
        </div>
        <p class="whitespace-pre-line">{{ message.content }}</p>
      </div>
      <div>
        <form v-if="!form.processing" @submit.prevent="submit">
          <div class=" text-right">
            <button class="btn" @click.prevent="form.targets.unshift({ body: '', isCommand: false })">入力枠を追加する</button>
          </div>
          <div v-for="(material, key) in form.targets" :key="key">
            <textarea class="mb-0" placeholder="指示に従って入力してください。" rows="3" v-model="material.body" />
            <Error :errorKey="`targets.${key}.body`" />
            <div class="flex justify-between items-start">
              <div>
                <input :id="`checkbox-${key}`" class="h-3 w-3" type="checkbox" v-model="material.isCommand">
                <label :for="`checkbox-${key}`" class="text-sm"
                  title="ここにチェックが入っている場合の内容ファシリテーターに送信されるのみで，DBに登録されません。質問や指示を送信したい場合に使用してください。">
                  これは質問や指示です。
                  <Error :errorKey="`targets.${key}.isCommand`" />
                </label>
              </div>
              <button v-if="form.targets.length > 1" class="btn mt-0 rounded-none rounded-br-md rounded-bl-md text-sm"
                @click.prevent="form.targets.splice(key, 1)">削除する</button>
            </div>
          </div>
          <button v-if="isShowLastMessage" type="submit" class="btn block w-full mt-3" :disabled="isReseting">送信</button>
          <small class="block text-center my-5" v-else>最後の会話を参照している時にのみ送信が可能です。</small>
        </form>
        <p v-else class="animate-bounce text-center">
          送信中
        </p>
        <div v-if="project.materials_count > project.vote_start" class="card text-center my-5">
          <p>「意見の評価」フェーズが解禁されました。</p>
          <Link class="link" :href="$route('evaluations.create', { project_id: project.id })">意見を評価する</Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from '@inertiajs/vue3'
import Back from "@/Assets/Back.vue";
import Error from "@/Assets/Error.vue";

export default defineComponent({
  data() {
    return {
      page: 1,
      isReseting: false,
      form: useForm({
        project_id: '',
        suggestion: '',
        sources: [],
        targets: [
          {
            body: '',
            isCommand: false
          }
        ],
      }),
    }
  },
  components: {
    AppLayout,
    Back,
    Error
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
    },
    isShowLastMessage: function () {
      return this.page === this.messages.length
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
          this.isReseting = true
        }.bind(this),
        onSuccess: function () {
          this.isReseting = false
        }.bind(this)
      })
    },
    submit: function () {
      this.form.project_id = this.project.id
      this.form.suggestion = this.messages[this.messages.length - 1].content
      this.form.sources = this.sources
      this.form.post('/materials', {
        onSuccess: function () {
          this.form.targets = [{ body: '', isCommand: false }]
          this.showLastMessage()
        }.bind(this)
      })
    },
  },
});
</script>
