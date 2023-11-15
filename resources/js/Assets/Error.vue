<template>
  <p v-if="$page.props.errors[errorKey]" class="text-red-500">{{ $page.props.errors[errorKey] }}</p>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
  props: {
    errorKey: String,
  },
  components: {
    Link,
  },
  computed: {
    errorKey: function () {
      return this.errorKey
    }
  }
});
</script>
