<template>
  <header>
    <div class="border-b border-zinc-900/20 bg-white dark:border-white/20 dark:bg-zinc-950">
      <div class="mx-auto max-w-7xl">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="relative flex justify-between py-3">
            <div class="flex flex-shrink-0 items-center">
              <Link :href="route('links.index')">
                <span class="sr-only">{{ $t('common.go_home') }}</span>
                <BlastLogo class="block h-7 w-auto sm:hidden" type="logomark" />
                <BlastLogo class="hidden h-7 w-auto sm:block" />
              </Link>
            </div>

            <div class="absolute inset-y-0 right-0 flex items-center space-x-4 sm:static sm:inset-auto sm:ml-6">
              <TeamSwitcher :user="user" />

              <ProfileMenu :user="user" />
            </div>
          </div>
        </div>

        <!-- Navigation -->
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

  <main class="space-y-10 py-10">
    <slot />
  </main>

  <FlashNotification />
</template>

<script lang="ts" setup>
import BlastLogo from '@/Components/Logo/BlastLogo.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { transChoice } from 'laravel-vue-i18n'
import { computed } from 'vue'
import { PageProps } from '@/types'
import FlashNotification from '@/Components/Notifications/FlashMessages.vue'
import TeamSwitcher from '@/Layouts/Partials/TeamSwitcher.vue'
import ProfileMenu from '@/Layouts/Partials/ProfileMenu.vue'
import { CurrentUser } from '@/types/models'

const user = computed<CurrentUser>(() => {
  return usePage<PageProps>().props.user as CurrentUser
})

interface NavLink {
  name: string
  href: string
  route: string
}

const links = computed<NavLink[]>(() => [
  {
    name: transChoice('Link|Links', 2),
    href: route('links.index'),
    route: '/links',
  },
  {
    name: transChoice('Team|Teams', 1),
    href: route('teams.show', user.value.current_team_id),
    route: '/teams',
  },
])
</script>
