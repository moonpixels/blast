<template>
  <TwoColumnForm
    :description="$t('auth.2fa_settings_description')"
    :title="$t('auth.2fa_settings_title')"
    data-cy="2fa-form"
  >
    <div class="max-w-md space-y-6 border-zinc-900/20 dark:border-white/20 rounded-md bg-white shadow-sm dark:bg-zinc-950 border p-3">
      <div v-if="!showSetup" class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <LockClosedIcon v-if="user.two_factor_enabled" class="h-5 w-5 text-emerald-600 dark:text-emerald-500" />
          <LockOpenIcon v-else class="h-5 w-5 text-rose-600 dark:text-rose-500" />
          <Badge :type="user.two_factor_enabled ? 'success' : 'danger'">
            {{ user.two_factor_enabled ? $t('common.enabled') : $t('common.disabled') }}
          </Badge>
        </div>

        <Link
          v-if="user.two_factor_enabled"
          :href="route('two-factor.disable')"
          :only="['auth.user']"
          as="button"
          method="delete"
          preserve-scroll
        >
          <SecondaryButton data-cy="disable-2fa-button">
            {{ $t('auth.2fa_settings_button_disable') }}
          </SecondaryButton>
        </Link>

        <Link
          v-else
          :href="route('two-factor.enable')"
          :only="['status', 'twoFactorQrCode', 'twoFactorRecoveryCodes']"
          as="button"
          method="post"
          preserve-scroll
        >
          <PrimaryButton data-cy="enable-2fa-button">
            {{ $t('auth.2fa_settings_button_enable') }}
          </PrimaryButton>
        </Link>
      </div>

      <template v-else>
        <article>
          <h3 class="text-sm text-zinc-900 dark:text-white font-medium">
            {{ $t('auth.2fa_settings_qr_code') }}
          </h3>
          <p class="text-sm mt-1">
            {{ $t('auth.2fa_settings_qr_code_description') }}
          </p>
          <div class="mt-4" data-cy="2fa-qr-code" v-html="twoFactorQrCode" />
        </article>

        <article>
          <h3 class="text-sm text-zinc-900 dark:text-white font-medium">
            {{ $t('auth.2fa_settings_recovery_codes') }}
          </h3>
          <p class="text-sm mt-1">
            {{ $t('auth.2fa_settings_recovery_codes_description') }}
          </p>
          <ul
            class="mt-4 rounded border border-zinc-900/20 dark:border-white/20 bg-zinc-50 dark:bg-zinc-900 p-3 text-xs font-mono select-all"
            data-cy="2fa-recovery-codes"
          >
            <li v-for="code in twoFactorRecoveryCodes" :key="code" v-text="code" />
          </ul>
        </article>

        <article>
          <h3 class="text-sm text-zinc-900 dark:text-white font-medium">
            {{ $t('auth.2fa_settings_confirm_code') }}
          </h3>
          <p class="text-sm mt-1">
            {{ $t('auth.2fa_settings_confirm_code_description') }}
          </p>
          <form class="space-y-4 mt-4" @submit.prevent="confirmCode">
            <TextInput
              v-model="form.code"
              :error="form.errors.confirmTwoFactorAuthentication?.code"
              :label="$t('common.two_factor_code')"
              autocomplete="one-time-code"
              required
              @input="form.clearErrors('code')"
            />

            <PrimaryButton :loading="form.processing" data-cy="submit-button" type="submit">
              {{ $t('auth.2fa_settings_button_confirm') }}
            </PrimaryButton>
          </form>
        </article>
      </template>
    </div>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import { User } from '@/types'
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import { InertiaForm, Link, useForm } from '@inertiajs/vue3'
import { LockClosedIcon, LockOpenIcon } from '@heroicons/vue/24/outline'
import Badge from '@/Components/Badges/Badge.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { computed } from 'vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import TwoFactorForm from '@/Pages/AccountSettings/Partials/TwoFactorForm.vue'

interface Props {
  user: User
  status?: string
  twoFactorQrCode?: string
  twoFactorRecoveryCodes?: string[]
}

const props = defineProps<Props>()

type TwoFactorForm = {
  code: string
}

interface TwoFactorInertiaForm extends InertiaForm<TwoFactorForm> {
  errors: Partial<Record<keyof TwoFactorForm, string>> & {
    confirmTwoFactorAuthentication?: {
      code?: string
    }
  }
}

const form = useForm<TwoFactorForm>({
  code: '',
}) as TwoFactorInertiaForm

const showSetup = computed<boolean>(() => {
  return props.status === 'two-factor-authentication-enabled' || form.hasErrors
})

function confirmCode(): void {
  form.post(route('two-factor.confirm'), {
    preserveScroll: true,
    only: ['auth.user', 'status', 'errors']
  })
}
</script>