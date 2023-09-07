<template>
  <TwoColumnBlockItem
    :description="$t('Permanently delete this team and all of its resources.')"
    :title="$t('Delete team')"
  >
    <AppAlert v-if="team.personal_team" data-cy="delete-personal-team-alert">
      {{ $t('You cannot delete your personal team.') }}
    </AppAlert>

    <div v-else class="flex h-full items-center">
      <ButtonDanger data-cy="delete-team-button" @click="showModal = true">
        {{ $t('Delete team') }}
      </ButtonDanger>
    </div>
  </TwoColumnBlockItem>

  <AppModal :show="showModal" :title="$t('Delete team')" data-cy="delete-team-modal" @close="showModal = false">
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
      <ButtonDanger :loading="form.processing" data-cy="delete-team-button" @click="submit">
        {{ $t('Delete team') }}
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
import { ref } from 'vue'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import AppModal from '@/components/AppModal.vue'
import { useForm } from '@inertiajs/vue3'
import AppAlert from '@/components/AppAlert.vue'
import { Team } from '@/types/models'

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
