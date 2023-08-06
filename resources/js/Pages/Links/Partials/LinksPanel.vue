<template>
  <ResourcePanel>
    <ResourcePanelHeader>
      <div />

      <div class="mt-3 lg:mt-0 lg:w-60">
        <TextInput
          v-model="searchQuery"
          :label="$t('Search')"
          :placeholder="$t('Find a link...')"
          data-cy="search-links-input"
          hide-label
          inverse
          type="search"
        >
          <template #icon>
            <MagnifyingGlassIcon class="pointer-events-none h-4 w-4" />
          </template>
        </TextInput>
      </div>
    </ResourcePanelHeader>

    <SimpleEmptyState
      v-if="!links.data.length"
      :description="
        searchQuery ? $t('There are no links matching your search.') : $t('There are no links for this team.')
      "
      :title="$t('No links')"
      data-cy="no-links-empty-state"
    >
      <template #icon>
        <LinkIcon class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
      </template>
    </SimpleEmptyState>

    <ResourcePanelList v-else data-cy="links-list">
      <ResourcePanelListItem v-for="link in links.data" :key="link.id">
        <template #content>
          <div class="flex items-center gap-3">
            <div>
              <a
                :href="link.destination_url"
                class="text-sm font-medium leading-6 text-zinc-900 hover:underline hover:decoration-dashed hover:underline-offset-4 dark:text-white"
                data-cy="short-url-link"
                rel="noopener noreferrer"
                target="_blank"
              >
                {{ link.short_url }}
              </a>
            </div>

            <div class="flex items-center gap-1">
              <div
                v-if="link.has_password"
                v-tippy="{ content: $t('Password protected') }"
                data-cy="password-protected-icon"
              >
                <LockClosedIcon
                  class="h-3.5 w-3.5 text-zinc-400 transition-all duration-200 ease-in-out hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300"
                />
              </div>

              <div
                v-if="link.expires_at"
                v-tippy="{ content: $t('Expires on :expiryDate', { expiryDate: useFormatDate(link.expires_at) }) }"
                data-cy="expiry-date-icon"
              >
                <ClockIcon
                  class="h-3.5 w-3.5 text-zinc-400 transition-all duration-200 ease-in-out hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300"
                />
              </div>

              <div
                v-if="link.visit_limit"
                v-tippy="{ content: $t('Limited to :limit visits', { limit: link.visit_limit.toString() }) }"
                data-cy="visit-limit-icon"
              >
                <CursorArrowRaysIcon
                  class="h-3.5 w-3.5 text-zinc-400 transition-all duration-200 ease-in-out hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300"
                />
              </div>
            </div>
          </div>

          <p class="truncate text-xs leading-5">
            {{ link.destination_url }}
          </p>
        </template>

        <template #actions>
          <SecondaryButton data-cy="copy-to-clipboard-button" size="icon" @click="copyLinkToClipboard(link, $event)">
            <span class="sr-only">{{ recentlyCopiedLink === link ? $t('Copied') : $t('Copy') }}</span>
            <ClipboardDocumentCheckIcon
              v-if="recentlyCopiedLink === link"
              class="h-4 w-4 text-zinc-900 dark:text-white"
            />
            <ClipboardDocumentIcon v-else class="h-4 w-4" />
          </SecondaryButton>

          <DropdownMenu data-cy="link-options-menu" size="xs">
            <template #button>
              <MenuButton
                :as="SecondaryButton"
                class="focus-ring rounded-full transition-all duration-200 ease-in-out"
                data-cy="link-options-menu-button"
                size="icon"
              >
                <span class="sr-only">{{ $t('Show options') }}</span>
                <EllipsisHorizontalIcon aria-hidden="true" class="h-4 w-4" />
              </MenuButton>
            </template>

            <template #menuItems>
              <MenuItemLink v-slot="{ active }" class="flex w-full items-center" href="#">
                <PencilSquareIcon
                  :class="[active ? 'text-zinc-900 dark:text-white' : '']"
                  aria-hidden="true"
                  class="-ml-2 mr-2 h-4 w-4"
                />
                {{ $t('Update link') }}
              </MenuItemLink>
              <DeleteLinkForm :link="link" />
            </template>
          </DropdownMenu>
        </template>
      </ResourcePanelListItem>
    </ResourcePanelList>

    <ResourcePanelFooter v-if="links.data.length">
      <PaginationTotals
        :paginated-resource="links"
        :resource-name="$tChoice('link|links', links.meta.total)"
        data-cy="pagination-totals"
      />

      <SimplePagination :paginated-resource="links" />
    </ResourcePanelFooter>
  </ResourcePanel>
</template>

<script lang="ts" setup>
import {
  ClipboardDocumentCheckIcon,
  ClipboardDocumentIcon,
  ClockIcon,
  CursorArrowRaysIcon,
  EllipsisHorizontalIcon,
  LockClosedIcon,
  MagnifyingGlassIcon,
  PencilSquareIcon,
} from '@heroicons/vue/20/solid'
import SimpleEmptyState from '@/Components/EmptyStates/SimpleEmptyState.vue'
import { LinkIcon } from '@heroicons/vue/24/outline'
import PaginationTotals from '@/Components/Pagination/PaginationTotals.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import SimplePagination from '@/Components/Pagination/SimplePagination.vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import ClipboardJS from 'clipboard'
import { ref, watch } from 'vue'
import ResourcePanel from '@/Components/ResourcePanel/ResourcePanel.vue'
import ResourcePanelHeader from '@/Components/ResourcePanel/ResourcePanelHeader.vue'
import ResourcePanelList from '@/Components/ResourcePanel/ResourcePanelList.vue'
import ResourcePanelListItem from '@/Components/ResourcePanel/ResourcePanelListItem.vue'
import ResourcePanelFooter from '@/Components/ResourcePanel/ResourcePanelFooter.vue'
import { useTippy } from 'vue-tippy'
import { trans } from 'laravel-vue-i18n'
import debounce from 'lodash/debounce'
import useFormatDate from '@/composables/useFormatDate'
import MenuItemLink from '@/Components/Dropdown/MenuItemLink.vue'
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue'
import { MenuButton } from '@headlessui/vue'
import DeleteLinkForm from '@/Pages/Links/Partials/DeleteLinkForm.vue'

export interface Filters {
  query?: string
}

interface Props {
  links: PaginatedResponse<Link>
  filters: Filters
}

const props = defineProps<Props>()

const searchQuery = ref<string>(props.filters.query ?? '')

const recentlyCopiedLink = ref<Link | null>(null)

let recentlyCopiedLinkTimeout: ReturnType<typeof setTimeout>

function copyLinkToClipboard(link: Link, event: Event): void {
  if (ClipboardJS.copy(link.short_url)) {
    recentlyCopiedLink.value = link

    if (recentlyCopiedLinkTimeout) {
      clearTimeout(recentlyCopiedLinkTimeout)
    }

    useTippy(event.currentTarget as HTMLButtonElement, {
      content: trans('Copied'),
      trigger: 'manual',
      onShow(instance) {
        setTimeout(() => {
          instance.destroy()
        }, 2000)
      },
    }).show()

    recentlyCopiedLinkTimeout = setTimeout(() => {
      recentlyCopiedLink.value = null
    }, 2000)
  }
}

function search() {
  router.reload({
    data: {
      query: searchQuery.value,
      page: 1,
    },
    only: ['filters', 'links'],
  })
}

watch(searchQuery, debounce(search, 500))
</script>
