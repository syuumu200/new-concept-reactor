<template>
  <AppLayout>
    <p class="card whitespace-pre-line">{{ message }}</p>
    <form @submit.prevent="submit">
      <textarea placeholder="指示に従って入力してください。" rows="6" v-model="form.body" />
      <button v-if="!form.processing" type="submit" class="btn block w-full">登録する</button>
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
        project_id: this.project.id,
        source: '',
        target: '',
        body: '',
      },
    }
  },
  components: {
    AppLayout,
  },
  props: {
    project: Object,
    message: String
  },
  methods: {
    submit() {
      router.post('/materials', this.form)
      this.form.body = ''
    },
  },
});
</script>
