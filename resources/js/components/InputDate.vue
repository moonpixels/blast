<template>
  <VDatePicker
    v-model.string="proxyModelValue"
    :masks="{ modelValue: 'YYYY-MM-DD\THH:mm:ssZZZZ' }"
    :popover="{ visibility: 'focus' }"
    color="blast"
    hide-time-header
    is-dark
    is24hr
    mode="dateTime"
  >
    <template #default="{ inputValue, inputEvents }">
      <BaseInput :value="inputValue" v-bind="{ ...$props, ...attrs }" v-on="inputEvents" />
    </template>

    <template #footer>
      <div class="w-full px-4 pb-3">
        <BaseButton class="w-full" size="sm" @click="() => (proxyModelValue = null)"> Clear</BaseButton>
      </div>
    </template>
  </VDatePicker>
</template>

<script lang="ts" setup>
import { computed, useAttrs } from 'vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseInput, { InputProps } from '@/components/BaseInput.vue'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps<Omit<InputProps, 'type'>>()

const emit = defineEmits<{
  'update:modelValue': [value: any]
}>()

const attrs = useAttrs()

const proxyModelValue = computed<string | null>({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  },
})
</script>
