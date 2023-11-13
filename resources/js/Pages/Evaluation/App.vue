<template>
  <AppLayout>
    <Back :href="$route('projects.show', project_id)" />
    <h1>意見を評価する</h1>
    <p class="card">{{ material.body }}</p>
    <div class="flex justify-evenly">
      <button class="link" @click="send(+1)">良い</button>
      <button class="link" @click="send(0)">どちらでもない</button>
      <button class="link" @click="send(-1)">悪い</button>
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
    project_id: String,
    material: Object
  },
  methods: {
    send(value) {
      router.post('evaluation', {
        project_id: this.project_id,
        material: this.material,
        value: value
      })
    }
  },
});
</script>
