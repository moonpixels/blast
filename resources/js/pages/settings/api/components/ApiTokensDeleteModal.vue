<template>
  <BaseButton size="icon" variant="plain" @click="open = true">
    <span class="sr-only">{{ $t('Delete token') }}</span>
    <TrashIcon
      aria-hidden="true"
      class="h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-rose-600 dark:group-hover:text-rose-500"
    />
  </BaseButton>

  <Modal :show="open" :title="$t('Delete token')" @close="open = false">
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Are you sure you want to delete the :token token? Any application using it will no longer be able to access your account.',
            {
              token: props.token.name,
            }
          )
        }}
      </p>
    </template>

    <template #footer>
      <BaseButton :loading="form.processing" variant="danger" @click="submit">
        {{ $t('Delete token') }}
      </BaseButton>

      <BaseButton variant="secondary" @click="open = false">
        {{ $t('Cancel') }}
      </BaseButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import { Token } from '@/types/models'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/components/BaseModal.vue'
import { TrashIcon } from '@heroicons/vue/20/solid'
import { ref } from 'vue'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  token: Token
}

const props = defineProps<Props>()

const open = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('personal-access-tokens.destroy', props.token.id), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      open.value = false
    },
  })
}
</script>
