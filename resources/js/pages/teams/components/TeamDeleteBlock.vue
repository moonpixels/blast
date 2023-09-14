<template>
  <TwoColumnBlockItem
    :description="$t('Permanently delete this team and all of its resources.')"
    :title="$t('Delete team')"
  >
    <BaseAlert v-if="team.personal_team" data-cy="delete-personal-team-alert">
      {{ $t('You cannot delete your personal team.') }}
    </BaseAlert>

    <div v-else class="flex h-full items-center">
      <BaseButton data-cy="delete-team-button" variant="danger" @click="showModal = true">
        {{ $t('Delete team') }}
      </BaseButton>
    </div>
  </TwoColumnBlockItem>

  <BaseModal :show="showModal" :title="$t('Delete team')" data-cy="delete-team-modal" @close="showModal = false">
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted, including its short links and members.'
          )
        }}
      </p>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" data-cy="delete-team-button" variant="danger" @click="submit">
        {{ $t('Delete team') }}
      </BaseButton>

      <BaseButton data-cy="cancel-button" variant="secondary" @click="showModal = false">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import { ref } from 'vue'
import BaseModal from '@/components/BaseModal.vue'
import { useForm } from '@inertiajs/vue3'
import BaseAlert from '@/components/BaseAlert.vue'
import { Team } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
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
