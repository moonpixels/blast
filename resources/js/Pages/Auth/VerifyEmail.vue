<template>
  <AppHead :title="$t('Verify your email')" />

  <AuthHeader :title="$t('Verify your email address')">
    <p>
      {{
        $t('To protect our platform from spam, we require you to verify your email address before you can continue.')
      }}
    </p>
  </AuthHeader>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-md">
    <Alert data-cy="email-verification-alert">
      <p class="text-sm">
        {{ $t('We have sent an email to ') }}

        <strong class="font-semibold underline decoration-violet-500 decoration-2 dark:decoration-violet-600">
          {{ email }}
        </strong>

        {{ $t(' with a verification link. Please click on the link to verify your email address.') }}
      </p>
    </Alert>

    <p class="text-center text-sm">
      {{ $t('If you did not receive the email') }},
      <InternalLink
        :href="route('verification.send')"
        as="button"
        data-cy="resend-email-button"
        method="post"
        type="button"
      >
        {{ $t('click here to request another.') }}
      </InternalLink>
    </p>
  </div>
</template>

<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import AuthHeader from '@/Pages/Auth/Partials/AuthHeader.vue'
import AppHead from '@/Components/App/AppHead.vue'
import Alert from '@/Components/Alerts/Alert.vue'
import InternalLink from '@/Components/Links/InternalLink.vue'

defineOptions({
  layout: AuthLayout,
})

interface Props {
  status?: string
  email: string
}

defineProps<Props>()
</script>
