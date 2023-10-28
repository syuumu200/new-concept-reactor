<template>
  <Head :title="$page.props.appName" />
  <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 pb-5">
    <div class="flex flex-col md:flex-row justify-between items-center">
      <h1 class="text-center text-4xl">
        <Link :href="$route('index')">{{ $page.props.appName }}</Link>
      </h1>
      <div class="text-center">
        <Link v-if="!$page.props.auth.check" class="link" :href="$route('login')">
        Microsoftでログイン
        </Link>
        <div v-else class="text-center flex gap-3">
          <span>{{ $page.props.auth.user.username }}</span>
          <Link class="link" :href="$route('logout')">ログアウト</Link>
        </div>
      </div>
    </div>
    <slot></slot>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
});
</script>
