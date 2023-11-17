<template>
  <Back class="absolute top-1 left-1" :href="$route('projects.show', project)" />
  <div class="grid md:grid-cols-4">
    <svg id="graph" class="md:col-span-3 hidden md:block md:h-screen md:w-screen"></svg>
    <div class="md:col-span-1 md:bg-gray-50 md:px-5">
      <div v-if="suggestion" class="whitespace-pre-line md:overflow-y-auto h-screen">{{ suggestion }}</div>
      <div class="mt-10 md:mt-20 text-center" v-else>
        <h1>意見の振り返り</h1>
        <p>ChatGPTが意見を整理し，考察を行います。文章の生成に時間がかかる場合があります。2分をすぎても出力されない場合は管理者に報告してください。</p>
        <button class="btn mt-3" @click="finish()" :disabled="isLoading">意見を振り返る</button>
      </div>
    </div>
  </div>
</template>

<script>
import { graphviz } from 'd3-graphviz';
import Back from "@/Assets/Back.vue";
import { router } from '@inertiajs/vue3';


export default {
  data() {
    return {
      isLoading: false
    }
  },
  components: {
    Back
  },
  props: {
    project: Object,
    materials: Array,
    edges: Array,
    suggestion: {
      type: String,
      default: ''
    }
  },
  mounted: function () {
    this.render()
  },
  methods: {
    render: async function () {
      let dot = `digraph {\n
   center=true;
      node [shape=ellipse margin=0.2 style=rounded fixedsize=false fontname="Zen Maru Gothic"]\n`
      this.materials.forEach(function (material) {
        dot += `"${material.id}"[label="${material.body}"]\n`
      })
      this.edges.forEach(function (edge) {
        dot += `"${edge.source}" -> "${edge.target}"\n`
      })
      dot += '}'

      let graph = await graphviz("#graph")
        .dot(dot)
        .zoomScaleExtent([0.1, 10]) // ズームの範囲を設定します。必要に応じて調整してください。
        .zoom(true) // ズームを有効にします。
        .render()
    },
    finish: function () {
      router.reload({
        only: ['suggestion'],
        onStart: () => this.isLoading = true,
        onFinish: () => this.isLoading = false
      })
    }
  }
}
</script>