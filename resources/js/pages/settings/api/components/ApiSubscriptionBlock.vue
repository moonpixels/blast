<template>
  <TwoColumnBlockItem
    :description="$t('Create, manage and cancel your subscription to our API service.')"
    :title="$t('Subscription')"
  >
    <div class="">
      <p>
        {{ $t('You are currently on the free plan, which includes 60 API requests per minute.', { plan: 'free' }) }}
      </p>

      <Alert class="mt-4">
        <strong class="font-semibold">
          {{ $t('Need more API requests?') }}
        </strong>

        <p class="mt-2">
          {{
            $t(
              'Our paid plan with an increased rate limit is currently in closed beta. We will be opening it up to everyone soon.'
            )
          }}
        </p>
      </Alert>
    </div>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import Alert from '@/components/BaseAlert.vue'
</script>
