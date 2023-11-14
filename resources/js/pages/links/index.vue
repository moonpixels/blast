<template>
  <BaseHead :title="$t('Links')" />

  <BaseContainer>
    <div class="space-y-6 lg:flex lg:space-x-6 lg:space-y-0">
      <div class="flex-none">
        <LinkShortener :shortened-link="shortenedLink" />
      </div>

      <div class="flex-auto overflow-hidden pb-10">
        <ResourcePanel list-cypress-selector="links-list">
          <template #header>
            <div />

            <div class="mt-3 lg:mt-0 lg:w-60">
              <BaseInput
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
              </BaseInput>
            </div>
          </template>

          <SimpleEmptyState
            v-if="!links.data.length"
            :description="
              searchQuery ? $t('There are no links matching your search.') : $t('There are no links for this team.')
            "
            :icon="LinkIcon"
            :title="$t('No links')"
            data-cy="no-links-empty-state"
          />

          <template v-if="links.data.length" #list>
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
                      v-tippy="{
                        content: $t('Expires on :expiryDate', { expiryDate: useFormatDate(link.expires_at) }),
                      }"
                      data-cy="expiry-date-icon"
                    >
                      <ClockIcon
                        class="h-3.5 w-3.5 text-zinc-400 transition-all duration-200 ease-in-out hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300"
                      />
                    </div>

                    <div
                      v-if="link.visit_limit"
                      v-tippy="{
                        content:
                          $t('Limited to :limit ', { limit: link.visit_limit.toString() }) +
                          $tChoice('visit|visits', link.visit_limit),
                      }"
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
                <BaseButton
                  data-cy="copy-to-clipboard-button"
                  size="icon"
                  variant="plain"
                  @click="copyLinkToClipboard(link, $event)"
                >
                  <span class="sr-only">{{ recentlyCopiedLink === link ? $t('Copied') : $t('Copy') }}</span>
                  <ClipboardDocumentCheckIcon
                    v-if="recentlyCopiedLink === link"
                    class="h-4 w-4 text-zinc-900 dark:text-white"
                  />
                  <ClipboardDocumentIcon v-else class="h-4 w-4" />
                </BaseButton>

                <DropdownMenu data-cy="link-options-menu" size="xs">
                  <template #button>
                    <MenuButton
                      :as="BaseButton"
                      class="focus-ring rounded-full transition-all duration-200 ease-in-out"
                      data-cy="link-options-menu-button"
                      size="icon"
                      variant="plain"
                    >
                      <span class="sr-only">{{ $t('Show options') }}</span>
                      <EllipsisHorizontalIcon aria-hidden="true" class="h-4 w-4" />
                    </MenuButton>
                  </template>

                  <template #menuItems>
                    <DropdownMenuItemButton
                      v-slot="{ active }"
                      class="flex w-full items-center"
                      data-cy="update-link-button"
                      @click="selectLink(link, 'update')"
                    >
                      <PencilSquareIcon
                        :class="[active ? 'text-zinc-900 dark:text-white' : '']"
                        aria-hidden="true"
                        class="-ml-2 mr-2 h-4 w-4"
                      />
                      {{ $t('Edit') }}
                    </DropdownMenuItemButton>

                    <DropdownMenuItemButton
                      v-slot="{ active }"
                      class="flex w-full items-center"
                      data-cy="delete-link-button"
                      @click="selectLink(link, 'delete')"
                    >
                      <TrashIcon
                        :class="[active ? 'text-rose-600 dark:text-rose-500' : '']"
                        aria-hidden="true"
                        class="-ml-2 mr-2 h-4 w-4"
                      />
                      {{ $t('Delete') }}
                    </DropdownMenuItemButton>
                  </template>
                </DropdownMenu>
              </template>
            </ResourcePanelListItem>
          </template>

          <template v-if="links.data.length" #footer>
            <PaginationTotals
              :paginated-resource="links"
              :resource-name="$tChoice('link|links', links.meta.total)"
              data-cy="pagination-totals"
            />

            <PaginationButtons :paginated-resource="links" />
          </template>
        </ResourcePanel>
      </div>
    </div>
  </BaseContainer>

  <LinkEditModal
    v-if="showUpdateLinkModal && currentLink"
    :link="currentLink"
    :open="showUpdateLinkModal"
    @close="showUpdateLinkModal = false"
  />

  <LinkDeleteModal
    v-if="showDeleteLinkModal && currentLink"
    :link="currentLink"
    :open="showDeleteLinkModal"
    @close="showDeleteLinkModal = false"
  />
</template>

<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue'
import BaseContainer from '@/components/BaseContainer.vue'
import LinkShortener from '@/components/LinkShortener.vue'
import { Link } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import BaseHead from '@/components/BaseHead.vue'
import useFormatDate from '@/composables/useFormatDate'
import PaginationButtons from '@/components/PaginationButtons.vue'
import {
  ClipboardDocumentCheckIcon,
  ClipboardDocumentIcon,
  ClockIcon,
  CursorArrowRaysIcon,
  EllipsisHorizontalIcon,
  LockClosedIcon,
  MagnifyingGlassIcon,
  PencilSquareIcon,
  TrashIcon,
} from '@heroicons/vue/20/solid'
import LinkDeleteModal from '@/pages/links/components/LinkDeleteModal.vue'
import PaginationTotals from '@/components/PaginationTotals.vue'
import SimpleEmptyState from '@/components/BaseEmptyState.vue'
import { LinkIcon } from '@heroicons/vue/24/outline'
import DropdownMenu from '@/components/DropdownMenu.vue'
import { MenuButton } from '@headlessui/vue'
import LinkEditModal from '@/pages/links/components/LinkEditModal.vue'
import ResourcePanel from '@/components/ResourcePanel.vue'
import ResourcePanelListItem from '@/components/ResourcePanelListItem.vue'
import BaseInput from '@/components/BaseInput.vue'
import DropdownMenuItemButton from '@/components/DropdownMenuItemButton.vue'
import { router } from '@inertiajs/vue3'
import ClipboardJS from 'clipboard'
import { ref, watch } from 'vue'
import { useTippy } from 'vue-tippy'
import { trans } from 'laravel-vue-i18n'
import debounce from 'lodash/debounce'
import BaseButton from '@/components/BaseButton.vue'

defineOptions({
  layout: AppLayout,
})

type Props = {
  shortenedLink?: Link
  links: PaginatedResponse<Link>
  filters: {
    search?: string
  }
}

const props = defineProps<Props>()

const searchQuery = ref<string>(props.filters.search ?? '')

const recentlyCopiedLink = ref<Link | null>(null)

let recentlyCopiedLinkTimeout: ReturnType<typeof setTimeout>

const currentLink = ref<Link | null>(null)

const showDeleteLinkModal = ref<boolean>(false)

const showUpdateLinkModal = ref<boolean>(false)

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
      filter: {
        search: searchQuery.value || undefined,
      },
      page: searchQuery.value ? 1 : undefined,
    },
    only: ['filters', 'links'],
  })
}

function selectLink(link: Link, modal: 'update' | 'delete') {
  currentLink.value = link

  if (modal === 'update') {
    showUpdateLinkModal.value = true
  } else {
    showDeleteLinkModal.value = true
  }
}

watch(searchQuery, debounce(search, 500))
</script>
