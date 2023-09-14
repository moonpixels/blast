<template>
  <BaseModal :show="open" :title="$t('Delete link')" data-cy="delete-link-modal" @close="$emit('close')">
    <template #body>
      <p class="text-sm">
        {{ $t('Are you sure you want to delete this link? The alias will not be available for use again.') }}
      </p>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" data-cy="delete-link-button" variant="danger" @click="submit">
        {{ $t('Delete link') }}
      </BaseButton>

      <BaseButton data-cy="cancel-button" variant="secondary" @click="$emit('close')">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script lang="ts" setup>
import BaseModal from '@/components/BaseModal.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  link: Link
  open: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
}>()

const form = useForm({})

function submit(): void {
  form.delete(route('links.destroy', props.link.id), {
    preserveScroll: true,
    onFinish: () => {
      emit('close')
    },
  })
}
</script>
