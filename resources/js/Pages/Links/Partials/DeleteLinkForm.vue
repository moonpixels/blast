<template>
  <MenuItemButton
    v-slot="{ active }"
    class="flex w-full items-center"
    data-cy="delete-link-button"
    @click.prevent="showModal = true"
  >
    <TrashIcon
      :class="[active ? 'text-rose-600 dark:text-rose-500' : '']"
      aria-hidden="true"
      class="-ml-2 mr-2 h-4 w-4"
    />
    {{ $t('Delete link') }}
  </MenuItemButton>

  <Modal :show="showModal" :title="$t('Delete link')" data-cy="delete-link-modal" @close="showModal = false">
    <template #body>
      <p class="text-sm">
        {{ $t('Are you sure you want to delete this link? The alias will not be available for use again.') }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-link-button" @click="submit">
        {{ $t('Delete link') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="showModal = false">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import { ref } from 'vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Modal from '@/Components/Modals/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@/types/models'
import MenuItemButton from '@/Components/Dropdown/MenuItemButton.vue'
import { TrashIcon } from '@heroicons/vue/20/solid'

interface Props {
  link: Link
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('links.destroy', props.link.id), {
    preserveScroll: true,
    onFinish: () => {
      showModal.value = false
    },
  })
}
</script>
