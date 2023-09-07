<template>
  <footer class="relative mx-auto mt-32 max-w-7xl px-4 sm:px-6 lg:px-8">
    <div
      class="flex-row-reverse items-center justify-between gap-6 space-y-6 border-t border-zinc-900/10 py-6 text-zinc-600/[.85] dark:border-white/10 dark:text-zinc-400/80 sm:flex"
    >
      <div></div>

      <div class="text-xs">
        {{ $t('Copyright') }} &copy; {{ year }}
        <a
          class="underline decoration-dashed underline-offset-2 transition-all duration-200 ease-in-out hover:text-zinc-900 dark:hover:text-white"
          href="https://moonpixels.co.uk"
        >
          Moon Pixels Ltd.
        </a>
        {{ $t('All rights reserved.') }}

        <span class="mt-2 block">
          {{ $t('Moon Pixels Ltd is a company registered in England and Wales with company number 14080344.') }}
        </span>

        <span class="mt-2 flex items-center gap-2">
          <AppLink :href="route('terms')">
            {{ $t('Terms of Service') }}
          </AppLink>

          <span>|</span>

          <AppLink :href="route('privacy')">
            {{ $t('Privacy Policy') }}
          </AppLink>
        </span>
      </div>
    </div>
  </footer>
</template>

<script lang="ts" setup>
import dayjs from 'dayjs'
import AppLink from '@/components/AppLink.vue'

const year = dayjs().year()
</script>
