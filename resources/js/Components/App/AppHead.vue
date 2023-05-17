<template>
  <Head>
    <title>
      {{ title }}
    </title>
  </Head>
</template>

<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { trans } from 'laravel-vue-i18n'

interface Props {
  title: string
}

const props = defineProps<Props>()

const title = computed<string>(() => {
  return `${props.title} - Blast` ?? trans('app.application_title')
})
</script>