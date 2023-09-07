<template>
  <TwoColumnBlockItem
    :description="$t('Permanently delete your account and all of its data and resources.')"
    :title="$t('Delete account')"
  >
    <div class="flex h-full items-center">
      <ButtonDanger data-cy="delete-account-button" @click="showModal = true">
        {{ $t('Delete account') }}
      </ButtonDanger>
    </div>
  </TwoColumnBlockItem>

  <AppModal
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
      <ButtonDanger :loading="form.processing" data-cy="delete-account-button" @click="submit">
        {{ $t('Delete account') }}
      </ButtonDanger>

      <ButtonSecondary data-cy="cancel-button" @click="showModal = false">
        {{ $t('Cancel') }}
      </ButtonSecondary>
    </template>
  </AppModal>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import ButtonDanger from '@/components/ButtonDanger.vue'
import { useForm } from '@inertiajs/vue3'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import { ref } from 'vue'
import AppModal from '@/components/AppModal.vue'

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
