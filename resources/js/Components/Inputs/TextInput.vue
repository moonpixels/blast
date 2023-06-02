<template>
  <div
    :class="[
      disabledClasses,
      borderClasses,
      backgroundClasses,
      'rounded-md border px-3 py-2 shadow-sm focus-within:ring-1',
    ]"
  >
    <label :class="[disabledClasses, 'block text-xs font-medium']" :for="id">
      {{ label }}
    </label>

    <input
      :id="id"
      ref="input"
      :class="[
        disabledClasses,
        'mt-1 block w-full truncate border-0 bg-transparent p-0 text-zinc-900 placeholder-zinc-400 focus:ring-0 dark:text-zinc-100 dark:placeholder-zinc-500 sm:text-sm',
      ]"
      :inputmode="inputMode"
      :type="type"
      :value="modelValue"
      v-bind="{ ...attrs }"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />

    <InputErrorMessage v-if="error" class="border-t border-zinc-900/20 pt-1 dark:border-white/20">
      {{ error }}
    </InputErrorMessage>
  </div>
</template>

<script lang="ts" setup>
import { nanoid } from 'nanoid'
import { computed, onMounted, ref, useAttrs } from 'vue'
import InputErrorMessage from '@/Components/Inputs/InputErrorMessage.vue'

defineOptions({
  inheritAttrs: false,
})

interface Props {
  id?: string
  type?: 'text' | 'email' | 'password' | 'number' | 'search' | 'tel' | 'url'
  error?: string
  label: string
  modelValue: any
  inverse?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  id: () => {
    return `text-input-${nanoid()}`
  },
  type: 'text',
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()

const attrs = useAttrs()

const input = ref<HTMLInputElement>()

type HTMLInputMode = 'search' | 'text' | 'none' | 'email' | 'tel' | 'url' | 'numeric' | 'decimal'

const inputMode = computed<HTMLInputMode>(() => {
  switch (attrs.inputmode ?? props.type) {
    case 'email':
      return 'email'
    case 'number':
      return 'numeric'
    case 'search':
      return 'search'
    case 'tel':
      return 'tel'
    case 'url':
      return 'url'
    default:
      return 'text'
  }
})

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

function select(): void {
  input.value?.select()
}

function setSelectionRange(start: number, end: number): void {
  input.value?.setSelectionRange(start, end)
}

onMounted(() => {
  if (attrs.autofocus) {
    focus()
  }
})
</script>
