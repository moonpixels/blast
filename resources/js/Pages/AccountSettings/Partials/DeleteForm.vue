<template>
  <TwoColumnForm :description="$t('account.delete_account_description')" :title="$t('account.delete_account_title')">
    <div class="flex h-full items-center">
      <DangerButton data-cy="delete-account-button" @click="showModal = true">
        {{ $t('account.delete_account_button') }}
      </DangerButton>
    </div>
  </TwoColumnForm>

  <Modal
    :show="showModal"
    :title="$t('account.delete_account_modal_title')"
    data-cy="delete-account-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="text-sm">
        {{ $t('account.delete_account_modal_text') }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-account-button" @click="submit">
        {{ $t('account.delete_account_button') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="showModal = false">
        {{ $t('common.cancel') }}
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
