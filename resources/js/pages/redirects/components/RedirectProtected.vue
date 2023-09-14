<template>
  <BaseHead :title="$t('Enter password for link')" />

  <FullPageHeading :title="$t('Password Required')">
    <p>
      {{ $t('Please enter the password for the link to continue.') }}
    </p>
  </FullPageHeading>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="authenticated-redirect-form" @submit.prevent="submit">
      <BaseInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('Password')"
        autocomplete="none"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <BaseButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Continue') }}
      </BaseButton>
    </form>
  </div>
</template>

<script lang="ts" setup>
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
import BaseInput from '@/components/BaseInput.vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  alias: string
}

const props = defineProps<Props>()

type AuthenticatedRedirectForm = {
  password: string
}

const form = useForm<AuthenticatedRedirectForm>({
  password: '',
})

function submit(): void {
  form.post(route('redirects.authenticate', props.alias))
}
</script>
