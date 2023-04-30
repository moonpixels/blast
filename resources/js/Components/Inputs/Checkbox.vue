<template>
  <div class="relative flex items-start">
    <div class="flex h-5 items-center">
      <input
        :id="id"
        ref="input"
        v-model="checked"
        :aria-describedby="description ? `${id}-description` : null"
        :value="value"
        class="h-4 w-4 rounded border-zinc-900/20 dark:border-white/20 text-violet-500 dark:text-violet-600 dark:checked:bg-violet-600 bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-900"
        type="checkbox"
        v-bind="{...$attrs}"
      />
    </div>

    <div class="ml-3 text-sm">
      <label :for="id" class="font-medium text-zinc-700 dark:text-zinc-200">
        {{ label }}
      </label>

      <p v-if="description" :id="`${id}-description`">
        {{ description }}
      </p>

      <InputErrorMessage v-if="error">
        {{ error }}
      </InputErrorMessage>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { nanoid } from 'nanoid'
import { computed, ref } from 'vue'
import InputErrorMessage from '@/Components/Inputs/InputErrorMessage.vue'

defineOptions({
  inheritAttrs: false,
})

interface Props {
  id?: string
  error?: string
  label: string
  description?: string
  value?: string | number
  modelValue: any
}

const props = withDefaults(defineProps<Props>(), {
  id: () => {
    return `checkbox-${nanoid()}`
  },
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()

const input = ref<HTMLInputElement>()

const checked = computed<Boolean>({
  get() {
    return props.value && Array.isArray(props.modelValue)
      ? props.modelValue.includes(props.value)
      : props.modelValue === props.value
  },
  set(value) {
    if (props.value && Array.isArray(props.modelValue)) {
      if (value) {
        emit('update:modelValue', [...props.modelValue, props.value])
      } else {
        emit(
          'update:modelValue',
          props.modelValue.filter((v: any) => v !== props.value),
        )
      }
    } else {
      emit('update:modelValue', value)
    }
  },
})

function focus(): void {
  input.value?.focus()
}
</script>
