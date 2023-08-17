<template>
  <SecondaryButton data-cy="delete-team-member-button" size="icon" @click="open = true">
    <span class="sr-only">{{ $t('Delete team member') }}</span>
    <TrashIcon
      aria-hidden="true"
      class="h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-rose-600 dark:group-hover:text-rose-500"
    />
  </SecondaryButton>

  <Modal :show="open" :title="$t('Delete team member')" data-cy="delete-team-member-modal" @close="open = false">
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Are you sure you want to delete :name from this team? They will no longer have access to the team’s resources and data.',
            { name: membership.user?.name as string }
          )
        }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-team-member-button" @click="submit">
        {{ $t('Delete team member') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="open = false">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import { TeamMembership } from '@/types/models'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modals/Modal.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { TrashIcon } from '@heroicons/vue/20/solid'
import { ref } from 'vue'

type Props = {
  membership: TeamMembership
}

const props = defineProps<Props>()

const open = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('team-memberships.destroy', props.membership.id), {
    preserveScroll: true,
    preserveState: true,
    only: ['flash', 'memberships'],
  })
}
</script>
