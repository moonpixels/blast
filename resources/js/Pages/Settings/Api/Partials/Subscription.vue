<template>
  <TwoColumnForm
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
              'Our paid plan is currently in development, but will be available soon. The paid plan will increase the API request limit.'
            )
          }}
        </p>
      </Alert>
    </div>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import Alert from '@/Components/Alerts/Alert.vue'
</script>
