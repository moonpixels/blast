<template>
  <Modal :show="open" :title="$t('Delete link')" data-cy="delete-link-modal" @close="$emit('close')">
    <template #body>
      <p class="text-sm">
        {{ $t('Are you sure you want to delete this link? The alias will not be available for use again.') }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-link-button" @click="submit">
        {{ $t('Delete link') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="$emit('close')">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Modal from '@/Components/Modals/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@/types/models'

interface Props {
  link: Link
  open: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'close'): void
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
