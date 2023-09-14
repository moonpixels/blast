<template>
  <BaseButton data-cy="invite-team-member-button" @click="showModal = true">
    {{ $t('Add member') }}
  </BaseButton>

  <BaseModal
    :show="showModal"
    :title="$t('Invite a new team member')"
    data-cy="invite-team-member-modal"
    @close="handleClose"
  >
    <template #body>
      <p class="mb-4 text-sm">
        {{
          $t(
            'Enter the email address of the person you would like to invite to this team. If they do not have an account, they will be prompted to create one.'
          )
        }}
      </p>

      <form id="invite-team-member-form" class="space-y-6" data-cy="invite-team-member-form" @submit.prevent="submit">
        <BaseInput
          v-model="form.email"
          :error="form.errors.email"
          :label="$t('Email')"
          inverse
          required
          type="email"
          @input="form.clearErrors('email')"
        />
      </form>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" data-cy="submit-button" form="invite-team-member-form" type="submit">
        {{ $t('Send invitation') }}
      </BaseButton>

      <BaseButton data-cy="cancel-button" variant="secondary" @click="handleClose">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script lang="ts" setup>
import BaseInput from '@/components/BaseInput.vue'
import BaseModal from '@/components/BaseModal.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Team } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'
import TeamInvitationData = App.Domain.Team.Data.TeamInvitationData

type Props = {
  team: Team
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm<TeamInvitationData>({
  email: '',
})

function submit(): void {
  form.post(route('teams.invitations.store', props.team.id), {
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
