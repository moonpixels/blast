<template>
  <ResourcePanel>
    <ResourcePanelHeader>
      <div />

      <div class="mt-3 lg:mt-0 lg:w-60">
        <TextInput
          v-model="searchForm.search"
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
        searchForm.search ? $t('There are no links matching your search.') : $t('There are no links for this team.')
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
          <a
            :href="link.destination_url"
            class="text-sm font-medium leading-6 text-zinc-900 hover:underline hover:decoration-dashed hover:underline-offset-4 dark:text-white"
            data-cy="short-url-link"
            rel="noopener noreferrer"
            target="_blank"
          >
            {{ link.short_url }}
          </a>

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

          <SecondaryButton data-cy="link-options-menu-button" size="icon">
            <span class="sr-only">{{ $t('Show options') }}</span>
            <EllipsisHorizontalIcon aria-hidden="true" class="h-4 w-4" />
          </SecondaryButton>
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
  EllipsisHorizontalIcon,
  MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'
import SimpleEmptyState from '@/Components/EmptyStates/SimpleEmptyState.vue'
import { LinkIcon } from '@heroicons/vue/24/outline'
import PaginationTotals from '@/Components/Pagination/PaginationTotals.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import SimplePagination from '@/Components/Pagination/SimplePagination.vue'
import { router, useForm } from '@inertiajs/vue3'
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

export interface Filters {
  search?: string
}

interface Props {
  links: PaginatedResponse<Link>
  filters: Filters
}

const props = defineProps<Props>()

type SearchForm = {
  search: string
}

const searchForm = useForm<SearchForm>({
  search: props.filters.search ?? '',
})

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
      search: searchForm.search,
      page: 1,
    },
    only: ['filters', 'links'],
  })
}

watch(() => searchForm.search, debounce(search, 500))
</script>
