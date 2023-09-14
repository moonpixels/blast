<template>
  <TwoColumnBlockItem
    :description="$t('Permanently remove yourself from the :team_name team.', { team_name: team.name })"
    :title="$t('Leave team')"
  >
    <div class="flex h-full items-center">
      <BaseButton data-cy="leave-team-button" variant="danger" @click="showModal = true">
        {{ $t('Leave team') }}
      </BaseButton>
    </div>
  </TwoColumnBlockItem>

  <BaseModal
    :show="showModal"
    :title="$t('Leave :team_name', { team_name: team.name })"
    data-cy="leave-team-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="text-sm">
        {{
          $t(
            "Are you sure you want to leave this team? This action cannot be undone and you'll need be invited back to access the team again."
          )
        }}
      </p>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" data-cy="leave-team-button" variant="danger" @click="submit">
        {{ $t('Leave team') }}
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
import { Team, User } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  team: Team
  user: User
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(
    route('teams.members.destroy', {
      team: props.team.id,
      member: props.user.id,
    }),
    {
      preserveScroll: true,
      onFinish: () => {
        showModal.value = false
      },
    }
  )
}
</script>
