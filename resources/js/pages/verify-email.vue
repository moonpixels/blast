<template>
  <AppHead :title="$t('Verify your email')" />

  <FullPageHeading :title="$t('Verify your email address')">
    <p>
      {{
        $t('To protect our platform from spam, we require you to verify your email address before you can continue.')
      }}
    </p>
  </FullPageHeading>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-md">
    <AppAlert data-cy="email-verification-alert">
      <p class="text-sm">
        {{ $t('We have sent an email to ') }}

        <strong class="font-semibold underline decoration-violet-500 decoration-2 dark:decoration-violet-600">
          {{ email }}
        </strong>

        {{ $t(' with a verification link. Please click on the link to verify your email address.') }}
      </p>
    </AppAlert>

    <p class="text-center text-sm">
      {{ $t('If you did not receive the email') }},
      <AppLink :href="route('verification.send')" as="button" data-cy="resend-email-button" method="post" type="button">
        {{ $t('click here to request another.') }}
      </AppLink>
    </p>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import AppHead from '@/components/AppHead.vue'
import AppAlert from '@/components/AppAlert.vue'
import AppLink from '@/components/AppLink.vue'

defineOptions({
  layout: FullPageLayout,
})

type Props = {
  status?: string
  email: string
}

defineProps<Props>()
</script>
