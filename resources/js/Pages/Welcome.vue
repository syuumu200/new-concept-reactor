<template>
  <AppLayout>
    <p class="card my-3">New ConceptReactorはかつて存在したConceptReactorというサービスの名前とドメインを再利用しつつ，ChatGPTを利用した発想支援・ファシリテーションシステムの概念実証をするために開発されたサービスです。</p>
    <div class="flex flex-col md:flex-row gap-3">
      <Link class="card link flex-1 text-center" :href="$route('projects.index')">プロジェクトの一覧</Link>
      <Link class="card link flex-1 text-center" :href="$route('projects.create')">プロジェクトを作成する</Link>
    </div>
    <p class="card my-3">広告募集中</p>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  components: {
    AppLayout,
  },
});
</script>
