<template>
  <div class="lg:sticky lg:top-8">
    <form
      class="relative z-10 w-full divide-y divide-zinc-900/10 rounded-md border border-zinc-900/20 bg-white shadow-sm dark:divide-white/10 dark:border-white/20 dark:bg-zinc-950 sm:w-96"
      data-cy="link-shortener-form"
      @submit.prevent="submit"
    >
      <div class="p-3">
        <BaseInput
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
              <PopoverButton :as="BaseButton" :data-cy="`set-${option.name}-button`" size="icon" variant="secondary">
                <span class="sr-only">{{ option.title }}</span>

                <span
                  v-if="
                    (form[option.name] && option.name !== 'team_id') ||
                    (option.name === 'team_id' && form.team_id !== user.current_team?.id)
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
              class="absolute inset-0 z-10 rounded-md bg-white/50 backdrop-blur transition-opacity dark:bg-zinc-900/50"
            />

            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="translate-y-1 opacity-0"
              enter-to-class="translate-y-0 opacity-100"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="translate-y-0 opacity-100"
              leave-to-class="translate-y-1 opacity-0"
            >
              <PopoverPanel
                v-slot="{ close }"
                class="absolute bottom-0 left-0 right-0 z-10 mt-6 rounded-bl-md rounded-br-md border-t border-zinc-900/20 bg-white/90 p-3 backdrop-blur dark:border-white/20 dark:bg-zinc-950/90"
                data-cy="link-options-popover"
              >
                <div class="absolute right-0 top-0 pr-2 pt-2">
                  <PopoverButton :as="ButtonDismiss" data-cy="dismiss-options-popover-button" />
                </div>

                <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                  {{ option.title }}
                </span>

                <p class="mt-1 text-sm">
                  {{ option.description }}
                </p>

                <div class="mt-6">
                  <BaseInput
                    v-if="option.name === 'alias'"
                    v-model="form.alias"
                    :error="form.errors.alias"
                    :label="$t('Alias')"
                    inverse
                    @input="form.clearErrors('alias')"
                    @keydown.enter.prevent="close"
                  />

                  <BaseInput
                    v-if="option.name === 'password'"
                    v-model="form.password"
                    :error="form.errors.password"
                    :label="$t('Password')"
                    inverse
                    type="password"
                    @input="form.clearErrors('password')"
                    @keydown.enter.prevent="close"
                  />

                  <InputDate
                    v-if="option.name === 'expires_at'"
                    v-model="form.expires_at"
                    :error="form.errors.expires_at"
                    :label="$t('Expires at')"
                    inverse
                    @input="form.clearErrors('expires_at')"
                    @keydown.enter.prevent="close"
                  />

                  <BaseInput
                    v-if="option.name === 'visit_limit'"
                    v-model="form.visit_limit"
                    :error="form.errors.visit_limit"
                    :label="$t('Visit limit')"
                    inputmode="numeric"
                    inverse
                    @input="form.clearErrors('visit_limit')"
                    @keydown.enter.prevent="close"
                  />

                  <InputSelect
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
                  </InputSelect>
                </div>
              </PopoverPanel>
            </transition>
          </Popover>
        </div>

        <div v-if="Object.keys(optionsErrors).length" class="mt-3" data-cy="link-options-errors">
          <InputErrorMessage v-for="(error, idx) in optionsErrors" :key="idx">
            {{ error }}
          </InputErrorMessage>
        </div>
      </div>

      <div class="p-3">
        <BaseButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
          <LinkIcon v-if="!form.processing" class="-ml-1 mr-2 h-4 w-4" />

          {{ $t('Shorten URL') }}
        </BaseButton>
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
          <BaseButton
            id="copy-to-clipboard"
            data-cy="copy-to-clipboard-button"
            size="icon"
            variant="plain"
            @click="copyLinkToClipboard"
          >
            <span class="sr-only">{{ recentlyCopiedLink ? $t('Copied') : $t('Copy') }}</span>
            <ClipboardDocumentCheckIcon v-if="recentlyCopiedLink" class="h-5 w-5 text-zinc-900 dark:text-white" />
            <ClipboardDocumentIcon v-else class="h-5 w-5" />
          </BaseButton>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script lang="ts" setup>
import BaseInput from '@/components/BaseInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ClipboardDocumentCheckIcon, ClipboardDocumentIcon, LinkIcon } from '@heroicons/vue/24/outline'
import { ClockIcon, CursorArrowRaysIcon, FolderIcon, LockClosedIcon, TagIcon } from '@heroicons/vue/20/solid'
import { Component as VueComponent, computed, ref } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { Link, User } from '@/types/models'
import { PageProps } from '@/types'
import ButtonDismiss from '@/components/ButtonDismiss.vue'
import { Popover, PopoverButton, PopoverOverlay, PopoverPanel } from '@headlessui/vue'
import InputErrorMessage from '@/components/InputErrorMessage.vue'
import InputSelect from '@/components/InputSelect.vue'
import ClipboardJS from 'clipboard'
import { Tippy, useTippy } from 'vue-tippy'
import InputDate from '@/components/InputDate.vue'
import BaseButton from '@/components/BaseButton.vue'
import LinkData = App.Domain.Link.Data.LinkData

type Props = {
  shortenedLink?: Link
}

const props = defineProps<Props>()

const user = computed<User>(() => {
  return usePage<PageProps>().props.user
})

const recentlyCopiedLink = ref<boolean>(false)

const form = useForm<LinkData>({
  destination_url: '',
  alias: undefined,
  password: null,
  expires_at: null,
  visit_limit: null,
  team_id: user.value.current_team?.id,
})

type LinkShortenerOption = {
  name: keyof LinkData
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
    name: 'visit_limit',
    title: trans('Set visit limit'),
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

const optionsErrors = computed<Record<keyof LinkData, string>>(() => {
  const keys = linkOptions.map((option) => option.name)

  const optionsErrors: Record<string, string> = {}

  for (const key of keys) {
    if (form.errors[key]) {
      optionsErrors[key] = form.errors[key] as string
    }
  }

  return optionsErrors
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
