<template>
  <TwoColumnForm :description="$t('teams.delete_section.description')" :title="$t('teams.delete_section.title')">
    <Alert v-if="team.personal_team" data-cy="delete-personal-team-alert">
      {{ $t('teams.delete_section.personal_team_text') }}
    </Alert>

    <div v-else class="flex h-full items-center">
      <DangerButton data-cy="delete-team-button" @click="showModal = true">
        {{ $t('teams.delete_section.form_button') }}
      </DangerButton>
    </div>
  </TwoColumnForm>

  <Modal
    :show="showModal"
    :title="$t('teams.delete_section.modal_title')"
    data-cy="delete-team-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="text-sm">
        {{ $t('teams.delete_section.modal_text') }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-team-button" @click="submit">
        {{ $t('teams.delete_section.form_button') }}
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
import { ref } from 'vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Modal from '@/Components/Modals/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import Alert from '@/Components/Alerts/Alert.vue'
import { Team } from '@/types/models'

interface Props {
  team: Team
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('teams.destroy', props.team.id), {
    preserveScroll: true,
    onFinish: () => {
      showModal.value = false
    },
  })
}
</script>
