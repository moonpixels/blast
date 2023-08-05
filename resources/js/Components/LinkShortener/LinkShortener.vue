<template>
  <div class="lg:sticky lg:top-8">
    <form
      class="relative z-10 w-full divide-y divide-zinc-900/10 overflow-hidden rounded-md border border-zinc-900/20 bg-white shadow-sm dark:divide-white/10 dark:border-white/20 dark:bg-zinc-950 sm:w-96"
      data-cy="link-shortener-form"
      @submit.prevent="submit"
    >
      <div class="p-3">
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
      </div>

      <div class="p-3">
        <div class="flex items-center gap-2">
          <Popover v-for="option in linkOptions" :key="option.name">
            <Tippy :content="option.title">
              <PopoverButton
                :as="SecondaryButton"
                :data-cy="`set-${option.name}-button`"
                class="relative overflow-hidden"
                size="icon"
              >
                <span class="sr-only">{{ option.title }}</span>

                <span
                  v-if="
                    (form[option.name] && option.name !== 'team_id') ||
                    (option.name === 'team_id' && form.team_id !== user.current_team_id)
                  "
                  :class="[
                    !!optionsErrors[option.name] ? 'bg-rose-500 dark:bg-rose-600' : 'bg-violet-500 dark:bg-violet-600',
                  ]"
                  class="absolute right-0.5 top-0.5 h-1.5 w-1.5 rounded-full"
                />

                <component :is="option.icon" class="h-4 w-4" />
              </PopoverButton>
            </Tippy>

            <PopoverOverlay
              class="absolute inset-0 z-10 bg-white/50 backdrop-blur transition-opacity dark:bg-zinc-900/50"
            />

            <Transition
              enter-active-class="ease-in-out duration-300"
              enter-from-class="opacity-0 translate-y-full"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="ease-in-out duration-200"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 translate-y-full"
            >
              <PopoverPanel
                v-slot="{ close }"
                class="absolute bottom-0 left-0 right-0 z-10 mt-6 border-t border-zinc-900/20 bg-white/90 p-3 backdrop-blur dark:border-white/20 dark:bg-zinc-950/90"
                data-cy="link-options-popover"
              >
                <div class="absolute right-0 top-0 pr-2 pt-2">
                  <PopoverButton :as="DismissButton" data-cy="dismiss-options-popover-button" />
                </div>

                <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                  {{ option.title }}
                </span>

                <p class="mt-1 text-sm">
                  {{ option.description }}
                </p>

                <div class="mt-6">
                  <TextInput
                    v-if="option.name === 'alias'"
                    v-model="form.alias"
                    :error="form.errors.alias"
                    :label="$t('Alias')"
                    inverse
                    @input="form.clearErrors('alias')"
                    @keydown.enter.prevent="close"
                  />

                  <TextInput
                    v-if="option.name === 'password'"
                    v-model="form.password"
                    :error="form.errors.password"
                    :label="$t('Password')"
                    inverse
                    type="password"
                    @input="form.clearErrors('password')"
                    @keydown.enter.prevent="close"
                  />

                  <DateInput
                    v-if="option.name === 'expires_at'"
                    v-model="form.expires_at"
                    :error="form.errors.expires_at"
                    :label="$t('Expires at')"
                    inverse
                    @input="form.clearErrors('expires_at')"
                    @keydown.enter.prevent="close"
                  />

                  <SelectInput
                    v-if="option.name === 'team_id'"
                    v-model="form.team_id"
                    :error="form.errors.team_id"
                    :label="$t('Team')"
                    inverse
                    @input="form.clearErrors('team_id')"
                    @keydown.enter.prevent="close"
                  >
                    <option v-for="team in user.teams" :key="team.id" :value="team.id">
                      {{ team.name }}
                    </option>
                  </SelectInput>
                </div>
              </PopoverPanel>
            </Transition>
          </Popover>
        </div>

        <div v-if="Object.keys(optionsErrors).length" class="mt-3" data-cy="link-options-errors">
          <InputErrorMessage v-for="(error, idx) in optionsErrors" :key="idx">
            {{ error }}
          </InputErrorMessage>
        </div>
      </div>

      <div class="p-3">
        <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
          <LinkIcon v-if="!form.processing" class="-ml-1 mr-2 h-4 w-4" />

          {{ $t('Shorten URL') }}
        </PrimaryButton>
      </div>
    </form>

    <Transition
      enter-active-class="ease-in-out duration-300"
      enter-from-class="opacity-95 -mt-6 -translate-y-full"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="ease-in-out duration-200"
      leave-from-class="opacity-100 translate-x-0"
      leave-to-class="opacity-0 -translate-x-full"
    >
      <div
        v-if="shortenedLink && !form.processing"
        class="z-0 mt-6 flex w-full items-center justify-between gap-4 overflow-hidden rounded-md border border-zinc-900/20 bg-white p-3 shadow-sm dark:border-white/20 dark:bg-zinc-950 sm:w-96"
        data-cy="shortened-link-card"
      >
        <div class="min-w-0 flex-1">
          <a
            :href="shortenedLink.destination_url"
            class="text-sm font-medium leading-6 text-zinc-900 hover:underline hover:decoration-dashed hover:underline-offset-4 dark:text-white"
            data-cy="short-url-link"
            rel="noopener noreferrer"
            target="_blank"
          >
            {{ shortenedLink.short_url }}
          </a>

          <span class="mt-1 block truncate text-xs" data-cy="shortened-link-destination">
            {{ shortenedLink.destination_url }}
          </span>
        </div>

        <div class="shrink-0">
          <SecondaryButton
            id="copy-to-clipboard"
            data-cy="copy-to-clipboard-button"
            size="icon"
            @click="copyLinkToClipboard"
          >
            <span class="sr-only">{{ recentlyCopiedLink ? $t('Copied') : $t('Copy') }}</span>
            <ClipboardDocumentCheckIcon v-if="recentlyCopiedLink" class="h-5 w-5 text-zinc-900 dark:text-white" />
            <ClipboardDocumentIcon v-else class="h-5 w-5" />
          </SecondaryButton>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script lang="ts" setup>
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import { ClipboardDocumentCheckIcon, ClipboardDocumentIcon, LinkIcon } from '@heroicons/vue/24/outline'
import { ClockIcon, CursorArrowRaysIcon, FolderIcon, LockClosedIcon, TagIcon } from '@heroicons/vue/20/solid'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { Component as VueComponent, computed, ref } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { CurrentUser, Link } from '@/types/models'
import { PageProps } from '@/types'
import DismissButton from '@/Components/Buttons/DismissButton.vue'
import { Popover, PopoverButton, PopoverOverlay, PopoverPanel } from '@headlessui/vue'
import InputErrorMessage from '@/Components/Inputs/InputErrorMessage.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import ClipboardJS from 'clipboard'
import { Tippy, useTippy } from 'vue-tippy'
import DateInput from '@/Components/Inputs/DateInput.vue'

interface Props {
  shortenedLink?: Link
}

const props = defineProps<Props>()

const user = computed<CurrentUser>(() => {
  return usePage<PageProps>().props.user as CurrentUser
})

const recentlyCopiedLink = ref<boolean>(false)

type LinkShortenerForm = {
  destination_url: string
  alias?: string
  password?: string
  expires_at?: string
  max_visits?: number
  team_id: string
}

const form = useForm<LinkShortenerForm>({
  destination_url: '',
  alias: undefined,
  password: undefined,
  expires_at: undefined,
  max_visits: undefined,
  team_id: user.value.current_team_id,
})

interface LinkShortenerOption {
  name: Exclude<keyof LinkShortenerForm, 'url'>
  title: string
  description: string
  icon: VueComponent
}

const linkOptions: LinkShortenerOption[] = [
  {
    name: 'alias',
    title: trans('Set custom alias'),
    description: trans('Set a custom alias for the link.'),
    icon: TagIcon,
  },
  {
    name: 'password',
    title: trans('Set password'),
    description: trans('Require a password to access the link.'),
    icon: LockClosedIcon,
  },
  {
    name: 'expires_at',
    title: trans('Set expiry date'),
    description: trans('Expire the link on a specific date.'),
    icon: ClockIcon,
  },
  {
    name: 'max_visits',
    title: trans('Set visits'),
    description: trans('Limit how many visits the link can receive.'),
    icon: CursorArrowRaysIcon,
  },
  {
    name: 'team_id',
    title: trans('Set team'),
    description: trans('Choose which team the link should belong to.'),
    icon: FolderIcon,
  },
]

const optionsErrors = computed(() => {
  return Object.fromEntries(
    Object.entries(form.errors).filter(([key]) => linkOptions.some((option) => option.name === key))
  )
})

function submit(): void {
  form.post(route('links.store'), {
    preserveScroll: true,
    only: ['errors', 'shortenedLink', 'links', 'flash'],
    onSuccess: () => {
      form.reset()
    },
  })
}

function copyLinkToClipboard(): void {
  if (props.shortenedLink) {
    const clipboard = new ClipboardJS('#copy-to-clipboard', {
      text: () => props.shortenedLink?.short_url ?? '',
    })

    clipboard.on('success', () => {
      clipboard.destroy()

      recentlyCopiedLink.value = true

      const copyButton = document.getElementById('copy-to-clipboard') as HTMLButtonElement

      useTippy(copyButton, {
        content: trans('Copied'),
        trigger: 'manual',
        onShow(instance) {
          setTimeout(() => {
            instance.destroy()
          }, 2000)
        },
      }).show()

      setTimeout(() => {
        recentlyCopiedLink.value = false
      }, 2000)
    })
  }
}
</script>
