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
            <SecondaryButton>
              {{ $t('Log in') }}
            </SecondaryButton>
          </Link>

          <Link :href="route('register')">
            <PrimaryButton>
              {{ $t('Register') }}
            </PrimaryButton>
          </Link>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <slot />
  </main>

  <TheFooter />
</template>
<script setup>
import { Link } from '@inertiajs/vue3'
import BlastLogo from '@/Components/Logo/BlastLogo.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import TheFooter from '@/Components/TheFooter.vue'
</script>
