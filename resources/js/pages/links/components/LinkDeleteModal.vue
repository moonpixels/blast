<template>
  <AppModal :show="open" :title="$t('Delete link')" data-cy="delete-link-modal" @close="$emit('close')">
    <template #body>
      <p class="text-sm">
        {{ $t('Are you sure you want to delete this link? The alias will not be available for use again.') }}
      </p>
    </template>

    <template #footer>
      <ButtonDanger :loading="form.processing" data-cy="delete-link-button" @click="submit">
        {{ $t('Delete link') }}
      </ButtonDanger>

      <ButtonSecondary data-cy="cancel-button" @click="$emit('close')">
        {{ $t('Cancel') }}
      </ButtonSecondary>
    </template>
  </AppModal>
</template>

<script lang="ts" setup>
import ButtonDanger from '@/components/ButtonDanger.vue'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import AppModal from '@/components/AppModal.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@/types/models'

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
