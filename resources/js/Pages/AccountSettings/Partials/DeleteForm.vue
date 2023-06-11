<template>
  <TwoColumnForm
    :description="$t('Permanently delete your account and all of its data and resources.')"
    :title="$t('Delete account')"
  >
    <div class="flex h-full items-center">
      <DangerButton data-cy="delete-account-button" @click="showModal = true">
        {{ $t('Delete account') }}
      </DangerButton>
    </div>
  </TwoColumnForm>

  <Modal
    :show="showModal"
    :title="$t('Are you sure you want to delete your account?')"
    data-cy="delete-account-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Once your account is deleted, all of its resources and data will be permanently removed. You cannot undo this action.'
          )
        }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-account-button" @click="submit">
        {{ $t('Delete account') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="showModal = false">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modals/Modal.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { ref } from 'vue'

const showModal = ref(false)

const form = useForm<{}>({})

function submit(): void {
  form.delete(route('current-user.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false
    },
  })
}
</script>
