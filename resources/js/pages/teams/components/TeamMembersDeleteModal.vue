<template>
  <ButtonSecondary data-cy="delete-team-member-button" size="icon" @click="open = true">
    <span class="sr-only">{{ $t('Delete team member') }}</span>
    <TrashIcon
      aria-hidden="true"
      class="h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-rose-600 dark:group-hover:text-rose-500"
    />
  </ButtonSecondary>

  <AppModal :show="open" :title="$t('Delete team member')" data-cy="delete-team-member-modal" @close="open = false">
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Are you sure you want to delete :name from this team? They will no longer have access to the team’s resources and data.',
            { name: member.name }
          )
        }}
      </p>
    </template>

    <template #footer>
      <ButtonDanger :loading="form.processing" data-cy="delete-team-member-button" @click="submit">
        {{ $t('Delete team member') }}
      </ButtonDanger>

      <ButtonSecondary data-cy="cancel-button" @click="open = false">
        {{ $t('Cancel') }}
      </ButtonSecondary>
    </template>
  </AppModal>
</template>

<script lang="ts" setup>
import { Team, User } from '@/types/models'
import { useForm } from '@inertiajs/vue3'
import AppModal from '@/components/AppModal.vue'
import ButtonDanger from '@/components/ButtonDanger.vue'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import { TrashIcon } from '@heroicons/vue/20/solid'
import { ref } from 'vue'

type Props = {
  team: Team
  member: User
}

const props = defineProps<Props>()

const open = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(
    route('teams.members.destroy', {
      team: props.team.id,
      member: props.member.id,
    }),
    {
      preserveScroll: true,
      preserveState: true,
      only: ['flash', 'members'],
      onSuccess: () => {
        open.value = false
      },
    }
  )
}
</script>
