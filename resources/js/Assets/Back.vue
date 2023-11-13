<template>
  <Link class="my-2" :href="href">
  <span class="material-icons text-sm">
    arrow_back_ios
  </span>
  <span>{{ label }}</span>
  </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
  props: {
    href: String,
    label: {
      type: String,
      default: '戻る'
    }
  },
  components: {
    Link,
  }
});
</script>
