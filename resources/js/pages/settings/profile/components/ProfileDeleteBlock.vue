<template>
  <TwoColumnBlockItem
    :description="$t('Permanently delete your account and all of its data and resources.')"
    :title="$t('Delete account')"
  >
    <div class="flex h-full items-center">
      <BaseButton data-cy="delete-account-button" variant="danger" @click="showModal = true">
        {{ $t('Delete account') }}
      </BaseButton>
    </div>
  </TwoColumnBlockItem>

  <BaseModal
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

      <p class="mt-2 text-sm">
        {{ $t('The following resources will be deleted:') }}
      </p>

      <ul class="mt-1 list-inside list-disc text-sm">
        <li>{{ $t('All of your owned teams.') }}</li>
        <li>{{ $t('All of the links associated with your owned teams.') }}</li>
        <li>{{ $t('Your user account and its data.') }}</li>
      </ul>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" data-cy="delete-account-button" variant="danger" @click="submit">
        {{ $t('Delete account') }}
      </BaseButton>

      <BaseButton data-cy="cancel-button" variant="secondary" @click="showModal = false">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import BaseModal from '@/components/BaseModal.vue'
import BaseButton from '@/components/BaseButton.vue'

const showModal = ref(false)

const form = useForm({})

function submit(): void {
  form.delete(route('user.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false
    },
  })
}
</script>
