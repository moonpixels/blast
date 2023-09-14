<template>
  <BaseHead :title="$t('Verify your email')" />

  <FullPageHeading :title="$t('Verify your email address')">
    <p>
      {{
        $t('To protect our platform from spam, we require you to verify your email address before you can continue.')
      }}
    </p>
  </FullPageHeading>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-md">
    <BaseAlert data-cy="email-verification-alert">
      <p class="text-sm">
        {{ $t('We have sent an email to ') }}

        <strong class="font-semibold underline decoration-violet-500 decoration-2 dark:decoration-violet-600">
          {{ email }}
        </strong>

        {{ $t(' with a verification link. Please click on the link to verify your email address.') }}
      </p>
    </BaseAlert>

    <p class="text-center text-sm">
      {{ $t('If you did not receive the email') }},
      <BaseLink
        :href="route('verification.send')"
        as="button"
        data-cy="resend-email-button"
        method="post"
        type="button"
      >
        {{ $t('click here to request another.') }}
      </BaseLink>
    </p>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
import BaseAlert from '@/components/BaseAlert.vue'
import BaseLink from '@/components/BaseLink.vue'

defineOptions({
  layout: FullPageLayout,
})

type Props = {
  status?: string
  email: string
}

defineProps<Props>()
</script>
