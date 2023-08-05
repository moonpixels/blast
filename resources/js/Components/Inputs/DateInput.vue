<template>
  <div
    :class="[
      disabledClasses,
      borderClasses,
      backgroundClasses,
      'rounded-md border px-3 py-2 shadow-sm focus-within:ring-1',
    ]"
  >
    <label :class="[disabledClasses, hideLabel ? 'sr-only' : '', 'mb-1 block text-xs font-medium']" :for="id">
      {{ label }}
    </label>

    <input
      :id="id"
      ref="input"
      v-mask="mask"
      :class="[
        disabledClasses,
        'block w-full truncate border-0 bg-transparent p-0 text-zinc-900 placeholder-zinc-400 focus:ring-0 dark:text-zinc-100 dark:placeholder-zinc-500 sm:text-sm',
      ]"
      :placeholder="dateFormatMask.toUpperCase()"
      :value="proxyModelValue"
      type="text"
      v-bind="{ ...attrs }"
      @accept="proxyModelValue = $event.detail.value"
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
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import { IMask, IMaskDirective } from 'vue-imask'
import customParseFormat from 'dayjs/plugin/customParseFormat'
import useFormatDate from '@/composables/useFormatDate'

defineOptions({
  inheritAttrs: false,
})

interface Props {
  id?: string
  error?: string
  label: string
  hideLabel?: boolean
  modelValue: any
  inverse?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  id: () => {
    return `date-input-${nanoid()}`
  },
  error: undefined,
  hideLabel: false,
  inverse: false,
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()

const attrs = useAttrs()

const input = ref<HTMLInputElement>()

const proxyModelValue = computed<string | null>({
  get() {
    return props.modelValue ? useFormatDate(props.modelValue, dateFormatMask.value) : null
  },
  set(value) {
    dayjs.extend(customParseFormat)
    const date = dayjs(value, dateFormatMask.value)
    if (date.isValid()) {
      emit('update:modelValue', date.format())
    } else {
      emit('update:modelValue', null)
    }
  },
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

const dateFormatMask = computed<string>(() => {
  dayjs.extend(utc)
  dayjs.extend(timezone)
  const userTimezone = dayjs.tz.guess()

  if (userTimezone.includes('America')) {
    return 'MM/DD/YYYY HH:mm'
  } else {
    return 'DD/MM/YYYY HH:mm'
  }
})

const vMask = IMaskDirective

const mask = {
  mask: Date,
  pattern: dateFormatMask.value,
  blocks: {
    DD: {
      mask: IMask.MaskedRange,
      from: 1,
      to: 31,
      maxLength: 2,
    },
    MM: {
      mask: IMask.MaskedRange,
      from: 1,
      to: 12,
      maxLength: 2,
    },
    YYYY: {
      mask: IMask.MaskedRange,
      from: 1900,
      to: 9999,
      maxLength: 4,
    },
    HH: {
      mask: IMask.MaskedRange,
      from: 0,
      to: 23,
      maxLength: 2,
    },
    mm: {
      mask: IMask.MaskedRange,
      from: 0,
      to: 59,
      maxLength: 2,
    },
  },
  format: (date: Date) => {
    return useFormatDate(date.toString(), dateFormatMask.value)
  },
  parse: (value: string) => {
    dayjs.extend(customParseFormat)
    return dayjs(value, dateFormatMask.value).toDate()
  },
}

function focus(): void {
  input.value?.focus()
}

onMounted(() => {
  if (attrs.autofocus) {
    focus()
  }
})
</script>
