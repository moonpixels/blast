<template>
  <header>
    <div class="border-b border-zinc-900/20 bg-white dark:border-white/20 dark:bg-zinc-950">
      <div class="mx-auto max-w-7xl">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="relative flex justify-between py-3">
            <div class="flex flex-shrink-0 items-center">
              <Link :href="route('links.index')">
                <span class="sr-only">{{ $t('common.go_home') }}</span>
                <BlastLogo class="block h-7 w-auto sm:hidden" variant="logomark" />
                <BlastLogo class="hidden h-7 w-auto sm:block" />
              </Link>
            </div>

            <div class="absolute inset-y-0 right-0 flex items-center space-x-4 sm:static sm:inset-auto sm:ml-6">
              <NavbarTeamSwitcher :user="user" />

              <NavbarProfileMenu :user="user" />
            </div>
          </div>
        </div>

        <div class="hide-scrollbar flex shrink-0 grow overflow-auto">
          <nav class="flex space-x-2 px-4 sm:px-6 lg:px-8" data-cy="main-navigation">
            <Link
              v-for="link in links"
              :key="link.name"
              :class="
                $page.url.startsWith(link.route)
                  ? 'border-violet-500 text-zinc-900 dark:border-violet-600 dark:text-white'
                  : 'border-transparent transition-all ease-in-out hover:text-zinc-900 dark:hover:text-white'
              "
              :href="link.href"
              class="group inline-flex items-center border-b-2 text-sm font-medium"
            >
              <span
                class="mb-1 rounded px-4 py-2 transition-all ease-in-out group-hover:bg-zinc-100 dark:group-hover:bg-zinc-800"
              >
                {{ link.name }}
              </span>
            </Link>
          </nav>
        </div>
      </div>
    </div>
  </header>
</template>

<script lang="ts" setup>
import BlastLogo from '@/components/BlastLogo.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { computed } from 'vue'
import { PageProps } from '@/types'
import NavbarTeamSwitcher from '@/components/NavbarTeamSwitcher.vue'
import { User } from '@/types/models'
import NavbarProfileMenu from '@/components/NavbarProfileMenu.vue'

const user = computed<User>(() => {
  return usePage<PageProps>().props.user
})

type NavLink = {
  name: string
  href: string
  route: string
}

const links = computed<NavLink[]>(() => [
  {
    name: trans('Links'),
    href: route('links.index'),
    route: '/links',
  },
  {
    name: trans('Teams'),
    href: route('teams.show', user.value.current_team?.id as string),
    route: '/teams',
  },
  {
    name: trans('API'),
    href: route('personal-access-tokens.index'),
    route: '/settings/api',
  },
])
</script>
