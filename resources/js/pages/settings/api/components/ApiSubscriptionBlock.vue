<template>
  <TwoColumnBlockItem
    :description="$t('Create, manage and cancel your subscription to our API service.')"
    :title="$t('Subscription')"
  >
    <div v-if="user.subscribed">
      <p class="text-sm">
        {{ $t('You are subscribed to the ') }}
        <strong class="font-medium text-zinc-900 dark:text-white">
          {{ $t('Pro plan') }}
        </strong>
        {{ $t(' which allows ') }}
        <strong class="font-medium text-zinc-900 dark:text-white">
          {{ $t('180 requests per minute') }}
        </strong>
        {{ $t(' to the API.') }}
      </p>

      <p class="mt-4 text-sm">
        {{ $t('Thanks for supporting Blast!') }}
        <span aria-label="Confetti" class="ml-1" role="img"> 🎉️</span>
      </p>

      <div class="mt-6">
        <a :href="route('subscriptions.edit')">
          <BaseButton variant="primary">
            {{ $t('Manage subscription') }}
          </BaseButton>
        </a>
      </div>
    </div>

    <div v-else>
      <p class="text-sm">
        {{ $t('You are subscribed to the ') }}
        <strong class="font-medium text-zinc-900 dark:text-white">
          {{ $t('free plan') }}
        </strong>
        {{ $t(' which allows ') }}
        <strong class="font-medium text-zinc-900 dark:text-white">
          {{ $t('10 requests per minute') }}
        </strong>
        {{ $t(' to the API.') }}
      </p>

      <Alert class="relative mt-6">
        <div class="absolute right-0 top-0 p-4">
          <strong class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
            {{ $t('$15') }}

            <span class="text-base font-medium leading-6 text-zinc-500 dark:text-zinc-400">
              {{ $t('/month') }}
            </span>
          </strong>
        </div>

        <div class="text-lg font-semibold">
          {{ $t('Blast Pro') }}
        </div>

        <div
          class="mt-4 inline-block rounded border border-dashed border-emerald-500 bg-emerald-500/10 px-2 py-2 font-medium text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-500"
        >
          {{ $t('180 requests per minute') }}
        </div>

        <p class="mt-4 max-w-lg">
          {{
            $t(
              "Our Pro plan is perfect for developers who want to integrate Blast into their application. It's generous rate limits allow you to make up to 180 requests per minute to the API."
            )
          }}
        </p>

        <div class="mt-6">
          <a :href="route('subscriptions.create')">
            <BaseButton variant="primary">
              {{ $t('Upgrade to Pro') }}
            </BaseButton>
          </a>
        </div>
      </Alert>
    </div>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import Alert from '@/components/BaseAlert.vue'
import BaseButton from '@/components/BaseButton.vue'
import { computed } from 'vue'
import { User } from '@/types/models'
import { usePage } from '@inertiajs/vue3'
import { PageProps } from '@/types'

const user = computed<User>(() => {
  return usePage<PageProps>().props.user
})
</script>
