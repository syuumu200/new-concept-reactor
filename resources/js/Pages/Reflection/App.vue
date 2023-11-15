<template>
  <Back class="absolute top-1 left-1" :href="$route('projects.index')" />
  <button class="absolute top-1 right-1" @click="finish()">まとめる</button>
  <div class="grid md:grid-cols-2">
    <svg class="invisible md:visible h-full w-full" id="graph"></svg>
    <div class="whitespace-pre-line md:overflow-y-auto h-screen">{{ suggestion }}</div>
  </div>
</template>

<script>
import { graphviz } from 'd3-graphviz'
import Back from "@/Assets/Back.vue";
import { router } from '@inertiajs/vue3'


export default {
  components: {
    Back
  },
  props: {
    materials: Array,
    edges: Array,
    suggestion: {
      type: String,
      default: 'Null'
    }
  },
  mounted: function () {
    this.render()
  },
  methods: {
    render: function () {
      let dot = `digraph {\n
   center=true;
      node [shape=ellipse margin=0.2 style=rounded fixedsize=false fontname="Zen Maru Gothic"]\n`
      this.materials.forEach(function (material) {
        dot += `"${material.id}"[label="${material.body}" URL="http://localhost:8000/"]\n`
      })
      this.edges.forEach(function (edge) {
        dot += `"${edge.source}" -> "${edge.target}"\n`
      })
      dot += '}'
      console.log(dot)
      graphviz("#graph")
        .dot(dot)
        .render();
    },
    finish: function () {
      router.reload({ only: ['suggestion'] })
    }
  }
}
</script>

<style scoped></style>