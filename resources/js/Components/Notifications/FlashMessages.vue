<template>
  <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-6 py-4 sm:py-6">
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
          class="pointer-events-auto relative w-full max-w-sm origin-bottom overflow-hidden rounded-md border border-zinc-900/20 bg-white shadow-md dark:border-white/20 dark:bg-zinc-950"
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

              <div class="absolute right-0 top-0 pr-2 pt-2">
                <DismissButton size="sm" @click="show = false" />
              </div>
            </div>
          </div>

          <span
            :key="notificationId"
            :style="{ width: `${loadingBarWidth}%` }"
            aria-hidden="true"
            class="absolute bottom-0 h-0.5 w-full bg-violet-500 transition-all duration-[5000ms] ease-linear dark:bg-violet-600"
          >
          </span>
        </div>
      </transition>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'
import { CheckCircleIcon, XCircleIcon } from '@heroicons/vue/20/solid'
import { router, usePage } from '@inertiajs/vue3'
import { FlashMessage, PageProps } from '@/types'
import DismissButton from '@/Components/Buttons/DismissButton.vue'
import { nanoid } from 'nanoid'

const show = ref<boolean>(false)

const notificationId = ref<string>(nanoid())

const loadingBarWidth = ref<number>(100)

let timeout: ReturnType<typeof setTimeout>

const success = computed<FlashMessage | undefined>(() => {
  return usePage<PageProps>().props.flash.success
})

const error = computed<FlashMessage | undefined>(() => {
  return usePage<PageProps>().props.flash.error
})

const flash = computed<FlashMessage | undefined>(() => {
  return success.value || error.value
})

function reset(): void {
  show.value = false
  clearTimeout(timeout)
  loadingBarWidth.value = 100
  notificationId.value = nanoid()
}

function showNotification(): void {
  show.value = true

  setTimeout(() => {
    loadingBarWidth.value = 0

    timeout = setTimeout(() => {
      show.value = false
      loadingBarWidth.value = 100
    }, 5000)
  }, 50)
}

onMounted(() => {
  if (flash.value) {
    showNotification()
  }
})

watch(flash, (value) => {
  if (value) {
    reset()
    showNotification()
  }
})

router.on('start', () => {
  reset()
})
</script>
