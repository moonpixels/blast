<template>
  <Head>
    <title>
      {{ title }}
    </title>

    <slot />
  </Head>
</template>

<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { trans } from 'laravel-vue-i18n'

type Props = {
  title: string
}

const props = defineProps<Props>()

const title = computed<string>(() => {
  return `${props.title} - Blast` ?? trans('Blast - The privacy-focused open source URL shortener')
})
</script>
