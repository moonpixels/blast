<template>
  <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end py-4 px-6 sm:py-6">
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div
          v-if="show"
          :data-cy="success ? 'success-notification' : 'error-notification'"
          class="pointer-events-auto origin-bottom w-full max-w-sm overflow-hidden rounded-md bg-white dark:bg-zinc-950 shadow-md border border-zinc-900/20 dark:border-white/20"
        >
          <div class="p-4">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <CheckCircleIcon v-if="success" aria-hidden="true" class="h-5 w-5 text-emerald-500" />
                <XCircleIcon v-else aria-hidden="true" class="h-5 w-5 text-rose-500" />
              </div>

              <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium text-zinc-900 dark:text-white">
                  {{ flash?.title }}
                </p>
                <p class="mt-1 text-sm">
                  {{ flash?.message }}
                </p>
              </div>

              <div class="ml-4 flex flex-shrink-0">
                <DismissButton size="sm" @click="show = false" />
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref, watch } from 'vue'
import { CheckCircleIcon, XCircleIcon } from '@heroicons/vue/20/solid'
import { usePage } from '@inertiajs/vue3'
import { FlashMessage, PageProps } from '@/types'
import DismissButton from '@/Components/Buttons/DismissButton.vue'

const show = ref(false)

const success = computed<FlashMessage | undefined>(() => {
  return usePage<PageProps>().props.flash.success
})

const error = computed<FlashMessage | undefined>(() => {
  return usePage<PageProps>().props.flash.error
})

const flash = computed<FlashMessage | undefined>(() => {
  return success.value || error.value
})

watch(flash, (value) => {
  if (value) {
    show.value = true
    setTimeout(() => {
      show.value = false
    }, 5000)
  }
})
</script>