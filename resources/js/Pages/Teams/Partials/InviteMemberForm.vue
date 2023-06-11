<template>
  <PrimaryButton data-cy="invite-team-member-button" @click="showModal = true">
    {{ $t('teams.members_section.invite_button') }}
  </PrimaryButton>

  <Modal
    :show="showModal"
    :title="$t('teams.members_section.invite_modal_title')"
    data-cy="invite-team-member-modal"
    @close="handleClose"
  >
    <template #body>
      <p class="mb-4 text-sm">
        {{ $t('teams.members_section.invite_modal_text') }}
      </p>

      <form id="invite-team-member-form" class="space-y-6" data-cy="invite-team-member-form" @submit.prevent="submit">
        <TextInput
          v-model="form.email"
          :error="form.errors.email"
          :label="$t('common.email')"
          inverse
          required
          type="email"
          @input="form.clearErrors('email')"
        />
      </form>
    </template>

    <template #footer>
      <PrimaryButton :loading="form.processing" data-cy="submit-button" form="invite-team-member-form" type="submit">
        {{ $t('teams.members_section.invite_modal_button') }}
      </PrimaryButton>

      <SecondaryButton data-cy="cancel-button" @click="handleClose">
        {{ $t('common.cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import Modal from '@/Components/Modals/Modal.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Team } from '@/types/models'

interface Props {
  team: Team
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

type InviteForm = {
  email: string
}

const form = useForm<InviteForm>({
  email: '',
})

function submit(): void {
  form.post(route('team-members.store', props.team.id), {
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
