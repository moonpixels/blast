<template>
  <div class="flex items-center gap-4 p-6">
    <Link :href="route('login')">
      <PrimaryButton>
        {{ $t('Log in') }}
      </PrimaryButton>
    </Link>

    <Link :href="route('register')">
      <PrimaryButton>
        {{ $t('Register') }}
      </PrimaryButton>
    </Link>
  </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'

defineProps<{
  laravelVersion: string
  phpVersion: string
}>()
</script>
