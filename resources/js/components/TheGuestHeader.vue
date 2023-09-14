<template>
  <header class="sticky top-0 z-10 w-full text-sm font-medium text-zinc-900 backdrop-blur-md dark:text-zinc-100">
    <nav class="relative mx-auto flex max-w-7xl items-center px-4 sm:px-6 lg:px-8">
      <div class="flex w-full items-center justify-between border-b border-zinc-900/10 py-2 dark:border-white/10">
        <div>
          <Link href="/">
            <span class="sr-only"> Blast home page </span>

            <BlastLogo class="h-7 w-auto" />
          </Link>
        </div>

        <div class="flex items-center space-x-2">
          <Link :href="route('login')">
            <BaseButton variant="plain">
              {{ $t('Log in') }}
            </BaseButton>
          </Link>

          <Link :href="route('register')">
            <BaseButton>
              {{ $t('Register') }}
            </BaseButton>
          </Link>
        </div>
      </div>
    </nav>
  </header>
</template>
<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import BlastLogo from '@/components/BlastLogo.vue'
import BaseButton from '@/components/BaseButton.vue'
</script>
