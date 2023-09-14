<template>
  <BaseButton @click="showModal = true">
    {{ $t('Create token') }}
  </BaseButton>

  <Modal :show="showModal" :title="$t('Create a new personal access token')" @close="handleClose">
    <template #body>
      <form id="create-token-form" class="space-y-6" @submit.prevent="submit">
        <BaseInput
          v-model="form.name"
          :error="form.errors.name"
          :label="$t('Name')"
          inverse
          required
          @input="form.clearErrors('name')"
        />
      </form>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" form="create-token-form" type="submit">
        {{ $t('Create token') }}
      </BaseButton>

      <BaseButton data-cy="cancel-button" variant="secondary" @click="handleClose">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import BaseInput from '@/components/BaseInput.vue'
import Modal from '@/components/BaseModal.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/components/BaseButton.vue'
import PersonalAccessTokenData = App.Domain.User.Data.PersonalAccessTokenData

const showModal = ref<boolean>(false)

const form = useForm<PersonalAccessTokenData>({
  name: '',
})

function submit(): void {
  form.post(route('personal-access-tokens.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      showModal.value = false
    },
  })
}

function handleClose(): void {
  form.reset()
  form.clearErrors()
  showModal.value = false
}
</script>
