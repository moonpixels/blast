<template>
  <div :class="[error ? 'border-rose-500 dark:border-rose-600 focus-within:border-rose-500 dark:focus-within:border-rose-600 focus-within:ring-rose-500 dark:focus-within:ring-rose-600' : 'border-zinc-900/20 dark:border-white/20 focus-within:border-violet-600 dark:focus-within:border-violet-500 focus-within:ring-violet-600 dark:focus-within:ring-violet-500', 'rounded-md bg-white shadow-sm dark:bg-zinc-950 border px-3 py-2 focus-within:ring-1']">
    <label :for="id" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400">
      {{ label }}
    </label>

    <input
      :id="id"
      ref="input"
      :inputmode="inputMode"
      :type="type"
      :value="modelValue"
      class="block mt-1 w-full truncate border-0 p-0 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-0 sm:text-sm bg-transparent"
      v-bind="{...attrs}"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />

    <InputErrorMessage v-if="error" class="mt-1 pt-1 border-t border-zinc-900/20 dark:border-white/20">
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
  id: string
  type: 'text' | 'email' | 'password' | 'number' | 'search' | 'tel' | 'url'
  error?: string
  label: string
  modelValue: any
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

const inputMode = computed(() => {
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

function focus() {
  input.value?.focus()
}

function select() {
  input.value?.select()
}

function setSelectionRange(start: number, end: number) {
  input.value?.setSelectionRange(start, end)
}

onMounted(() => {
  if (attrs.autofocus) {
    focus()
  }
})
</script>