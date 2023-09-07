<template>
  <PrimaryButton @click="showModal = true">
    {{ $t('Create token') }}
  </PrimaryButton>

  <Modal :show="showModal" :title="$t('Create a new personal access token')" @close="handleClose">
    <template #body>
      <form id="create-token-form" class="space-y-6" @submit.prevent="submit">
        <TextInput
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
      <PrimaryButton :loading="form.processing" form="create-token-form" type="submit">
        {{ $t('Create token') }}
      </PrimaryButton>

      <SecondaryButton data-cy="cancel-button" @click="handleClose">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import PrimaryButton from '@/components/ButtonPrimary.vue'
import TextInput from '@/components/AppInput.vue'
import Modal from '@/components/AppModal.vue'
import SecondaryButton from '@/components/ButtonSecondary.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
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
