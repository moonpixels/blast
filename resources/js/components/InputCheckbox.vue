<template>
  <div class="relative flex items-start">
    <div class="flex h-5 items-center">
      <input
        :id="id"
        ref="input"
        v-model="checked"
        :aria-describedby="description ? `${id}-description` : undefined"
        :value="value"
        class="focus-ring h-4 w-4 rounded border-zinc-900/20 bg-white text-violet-500 dark:border-white/20 dark:bg-zinc-800 dark:text-violet-600 dark:checked:bg-violet-600"
        type="checkbox"
        v-bind="{ ...$attrs }"
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
import InputErrorMessage from '@/components/InputErrorMessage.vue'

defineOptions({
  inheritAttrs: false,
})

type Props = {
  id?: string
  error?: string
  label: string
  description?: string
  value?: string | number | boolean
  modelValue: any
}

const props = withDefaults(defineProps<Props>(), {
  id: () => {
    return `checkbox-${nanoid()}`
  },
  error: undefined,
  description: undefined,
  value: true,
})

const emit = defineEmits<{
  'update:modelValue': [value: any]
}>()

const input = ref<HTMLInputElement>()

const checked = computed<boolean>({
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
          props.modelValue.filter((v: any) => v !== props.value)
        )
      }
    } else {
      emit('update:modelValue', value)
    }
  },
})
</script>
