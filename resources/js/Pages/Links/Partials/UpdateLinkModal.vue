<template>
  <Modal :show="open" :title="$t('Update link')" data-cy="update-link-modal" @close="$emit('close')">
    <template #body>
      <form id="update-link-form" class="space-y-6" data-cy="update-link-form" @submit.prevent="submit">
        <TextInput
          v-model="form.destination_url"
          :error="form.errors.destination_url"
          inputmode="url"
          inverse
          label="URL"
          placeholder="https://example.com"
          required
          @input="form.clearErrors('destination_url')"
        />

        <TextInput
          v-model="form.alias"
          :error="form.errors.alias"
          :label="$t('Alias')"
          inverse
          @input="form.clearErrors('alias')"
        />

        <div class="space-y-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:space-y-0">
          <DateInput
            v-model="form.expires_at"
            :error="form.errors.expires_at"
            :label="$t('Expires at')"
            inverse
            @input="form.clearErrors('expires_at')"
          />

          <TextInput
            v-model="form.visit_limit"
            :error="form.errors.visit_limit"
            :label="$t('Visit limit')"
            inputmode="numeric"
            inverse
            @input="form.clearErrors('visit_limit')"
          />
        </div>

        <SelectInput
          v-model="form.team_id"
          :error="form.errors.team_id"
          :label="$t('Team')"
          inverse
          @input="form.clearErrors('team_id')"
        >
          <option v-for="team in user.teams" :key="team.id" :value="team.id">
            {{ team.name }}
          </option>
        </SelectInput>

        <Checkbox v-model="passwordProtected" :label="$t('Require a password to access the link')" />

        <TextInput
          v-if="passwordProtected"
          v-model="form.password"
          :error="form.errors.password"
          :label="$t('Password')"
          :placeholder="link.has_password ? $t('Leave blank to keep the current password') : ''"
          inverse
          type="password"
          @input="form.clearErrors('password')"
        />
      </form>
    </template>

    <template #footer>
      <PrimaryButton :loading="form.processing" data-cy="submit-button" form="update-link-form" type="submit">
        {{ $t('Update link') }}
      </PrimaryButton>

      <SecondaryButton data-cy="cancel-button" @click="$emit('close')">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Modal from '@/Components/Modals/Modal.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { CurrentUser, Link } from '@/types/models'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import { LinkShortenerForm } from '@/Components/LinkShortener/LinkShortener.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import DateInput from '@/Components/Inputs/DateInput.vue'
import { computed, ref } from 'vue'
import { PageProps } from '@/types'
import dayjs from 'dayjs'
import Checkbox from '@/Components/Inputs/Checkbox.vue'

interface Props {
  link: Link
  open: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'close'): void
}>()

const user = computed<CurrentUser>(() => {
  return usePage<PageProps>().props.user as CurrentUser
})

const passwordProtected = ref<boolean>(props.link.has_password)

const form = useForm<LinkShortenerForm>({
  destination_url: props.link.destination_url,
  alias: props.link.alias || undefined,
  password: '',
  expires_at: props.link.expires_at ? dayjs(props.link.expires_at).format() : undefined,
  visit_limit: props.link.visit_limit || undefined,
  team_id: props.link.team_id,
})

function submit(): void {
  form
    .transform((data) => ({
      ...data,
      alias: data.alias || undefined,
      password: passwordProtected.value && !data.password ? undefined : data.password,
    }))
    .put(route('links.update', props.link.id), {
      preserveScroll: true,
      onSuccess: () => {
        emit('close')
      },
    })
}
</script>
