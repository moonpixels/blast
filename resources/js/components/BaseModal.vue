<template>
  <Teleport to="body">
    <TransitionRoot :show="show" as="template">
      <Dialog as="div" class="relative z-10" @close="$emit('close')">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-zinc-900/50 backdrop-blur transition-opacity" />
        </TransitionChild>

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enter-to="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 translate-y-0 sm:scale-100"
              leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <DialogPanel
                class="relative transform rounded-lg border border-zinc-900/20 text-left shadow-lg transition-all dark:border-white/20 sm:my-8 sm:w-full sm:max-w-lg"
                v-bind="{ ...attrs }"
              >
                <div class="overflow-hidden rounded-lg bg-white dark:bg-zinc-950">
                  <div class="absolute right-0 top-0 hidden pr-3 pt-3 sm:block">
                    <ButtonDismiss @click="$emit('close')" />
                  </div>

                  <div class="bg-white p-4 dark:bg-zinc-950 sm:p-6">
                    <DialogTitle as="h3" class="mb-4 text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                      {{ title }}
                    </DialogTitle>

                    <slot name="body" />
                  </div>

                  <div class="flex flex-row-reverse gap-3 bg-zinc-50 px-4 py-3 dark:bg-zinc-900 sm:px-6">
                    <slot name="footer" />
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </Teleport>
</template>

<script lang="ts" setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import ButtonDismiss from '@/components/ButtonDismiss.vue'
import { useAttrs } from 'vue'

defineOptions({
  inheritAttrs: false,
})

const attrs = useAttrs()

type Props = {
  show: boolean
  title: string
}

defineProps<Props>()

defineEmits<{
  close: []
}>()
</script>
