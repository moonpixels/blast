<template>
  <TwoColumnForm
    :description="$t('View the API documentation to integrate Blast into your application.')"
    :title="$t('API docs')"
  >
    <div class="flex h-full items-center">
      <a href="https://blst.to/docs">
        <PrimaryButton>
          {{ $t('View API docs') }}
        </PrimaryButton>
      </a>
    </div>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
</script>
