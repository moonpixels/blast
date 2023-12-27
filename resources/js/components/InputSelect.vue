<template>
  <div
    :class="[
      disabledClasses,
      borderClasses,
      backgroundClasses,
      'rounded-md border px-3 py-2 shadow-sm focus-within:ring-1',
    ]"
  >
    <label v-if="!hideLabel" :class="[disabledClasses, 'mb-1 block text-xs font-medium']" :for="id">
      {{ label }}
    </label>

    <select
      :id="id"
      ref="input"
      :class="[
        disabledClasses,
        'block w-full truncate border-0 bg-transparent p-0 text-zinc-900 placeholder-zinc-400 focus:ring-0 sm:text-sm dark:text-zinc-100 dark:placeholder-zinc-500',
      ]"
      :value="modelValue"
      v-bind="{ ...attrs }"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    >
      <slot />
    </select>

    <InputErrorMessage v-if="error" class="border-t border-zinc-900/20 pt-1 dark:border-white/20">
      {{ error }}
    </InputErrorMessage>
  </div>
</template>

<script lang="ts" setup>
import { nanoid } from 'nanoid'
import { computed, onMounted, ref, useAttrs } from 'vue'
import InputErrorMessage from '@/components/InputErrorMessage.vue'

defineOptions({
  inheritAttrs: false,
})

type Props = {
  id?: string
  error?: string
  label: string
  hideLabel?: boolean
  modelValue: any
  inverse?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  id: () => {
    return `select-input-${nanoid()}`
  },
  error: undefined,
  hideLabel: false,
  inverse: false,
})

defineEmits<{
  'update:modelValue': [value: any]
}>()

const attrs = useAttrs()

const input = ref<HTMLInputElement>()

const disabledClasses = computed<string>(() => {
  return attrs.disabled !== undefined ? 'cursor-not-allowed opacity-75' : ''
})

const borderClasses = computed<string>(() => {
  return props.error
    ? 'border-rose-500 dark:border-rose-600 focus-within:border-rose-500 dark:focus-within:border-rose-600 focus-within:ring-rose-500 dark:focus-within:ring-rose-600'
    : 'border-zinc-900/20 dark:border-white/20 focus-within:border-violet-500 dark:focus-within:border-violet-600 focus-within:ring-violet-500 dark:focus-within:ring-violet-600'
})

const backgroundClasses = computed<string>(() => {
  return props.inverse ? 'bg-zinc-50 dark:bg-zinc-900' : 'bg-white dark:bg-zinc-950'
})

function focus(): void {
  input.value?.focus()
}

onMounted(() => {
  if (attrs.autofocus) {
    focus()
  }
})
</script>
