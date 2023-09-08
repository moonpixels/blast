<template>
  <Head>
    <title>
      {{ title }}
    </title>

    <meta content="Blast" name="apple-mobile-web-app-title" />
    <meta content="Blast" name="application-name" />
    <meta content="#09090b" name="theme-color" />
    <meta content="website" name="og:type" />
    <meta content="/images/social/social-card-large.jpg" name="og:image" />
    <meta content="/images/social/social-card-large.jpg" name="twitter:image" />
    <meta content="summary_large_image" name="twitter:card" />

    <link href="/images/favicons/favicon-512x512.png?v=1" rel="icon" sizes="512x512" type="image/png" />
    <link href="/images/favicons/favicon-256x256.png?v=1" rel="icon" sizes="256x256" type="image/png" />
    <link href="/images/favicons/favicon-128x128.png?v=1" rel="icon" sizes="128x128" type="image/png" />
    <link href="/images/favicons/favicon-64x64.png?v=1" rel="icon" sizes="64x64" type="image/png" />
    <link href="/images/favicons/favicon-32x32.png?v=1" rel="icon" sizes="32x32" type="image/png" />
    <link href="/images/favicons/apple-touch-icon.png?v=1" rel="apple-touch-icon" sizes="180x180" type="image/png" />

    <meta
      content="Privacy focused link shortening for developers. Blast allows you to easily integrate link shortening into your application, while keeping your users data private."
      head-key="description"
      name="description"
    />

    <slot />
  </Head>
</template>

<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { trans } from 'laravel-vue-i18n'

type Props = {
  title?: string
}

const props = defineProps<Props>()

const title = computed<string>(() => {
  return props.title ? `${props.title} - Blast` : trans('Blast - The privacy focused URL shortener for developers')
})
</script>
