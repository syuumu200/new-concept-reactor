<template>
  <div class="flex justify-end pr-5">
    <Link class="
      material-icons-outlined
      text-6xl
      text-gray-400
      hover:text-gray-300
      fixed
      bottom-5
      max-w-4xl
      z-10
    " :href="href">add_circle</Link>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
  props: {
    href: String
  },
  components: {
    Link,
  }
});
</script>
