<template>
  <header>
    <div class="bg-white dark:bg-zinc-950 border-b border-zinc-900/20 dark:border-white/20">
      <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex py-3 justify-between">
          <!-- Mobile navigation -->
          <Popover class="absolute inset-y-0 left-0 flex items-center sm:hidden">
            <PopoverButton
              class="inline-flex items-center justify-center rounded p-2 transition-all ease-in-out hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-zinc-950"
            >
              <span class="sr-only">{{ $t('common.open_menu') }}</span>
              <Bars3Icon aria-hidden="true" class="block h-6 w-6" />
            </PopoverButton>

            <PopoverOverlay class="fixed inset-0 z-50 backdrop-blur bg-zinc-50/50 dark:bg-zinc-900/50" />

            <transition
              enter-active-class="duration-200 ease-out"
              enter-from-class="scale-95 opacity-0"
              enter-to-class="scale-100 opacity-100"
              leave-active-class="duration-250 ease-in"
              leave-from-class="scale-100 opacity-100"
              leave-to-class="scale-95 opacity-0"
            >
              <PopoverPanel
                v-slot="{ close }"
                class="fixed inset-x-4 top-4 z-50 origin-bottom rounded-md p-4 bg-white dark:bg-zinc-950 border border-zinc-900/20 dark:border-white/20"
              >
                <div class="flex flex-row-reverse items-center justify-between">
                  <PopoverButton>
                    <XMarkIcon class="h-6 w-6" />
                  </PopoverButton>

                  <Link :href="route('links')" @click="close">
                    <span class="sr-only">{{ $t('common.go_home') }}</span>
                    <BlastLogo class="h-8 w-8" type="logomark" />
                  </Link>
                </div>

                <nav class="mt-4">
                  <ul class="divide-y text-base divide-zinc-900/10 dark:divide-white/10">
                    <li v-for="link in links" :key="link.name">
                      <Link :href="link.href" class="block py-2.5" @click="close">
                        <span :class="{'text-zinc-900 dark:text-white': $page.url.startsWith(link.route)}">
                          {{ link.name }}
                        </span>
                      </Link>
                    </li>
                  </ul>
                </nav>
              </PopoverPanel>
            </transition>
          </Popover>

          <!-- Logo -->
          <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
            <div class="flex flex-shrink-0 items-center">
              <Link :href="route('links')">
                <span class="sr-only">{{ $t('common.go_home') }}</span>
                <BlastLogo class="block h-7 w-auto sm:hidden" type="logomark" />
                <BlastLogo class="hidden h-7 w-auto sm:block" />
              </Link>
            </div>
          </div>

          <!-- Profile dropdown -->
          <div
            class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
          >
            <DropdownMenu>
              <template #button>
                <MenuButton
                  class="flex rounded-full bg-white dark:bg-zinc-950 text-sm transition-all ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-zinc-950"
                >
                  <span class="sr-only">{{ $t('common.open_menu') }}</span>
                  <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-500">
                    <span class="text-sm font-medium leading-none text-white">
                      {{ user.initials }}
                    </span>
                  </span>
                </MenuButton>
              </template>

              <template #menuItems>
                <MenuItemLink :href="route('account-settings')">
                  {{ $t('app.user_menu.account_settings') }}
                </MenuItemLink>
                <MenuItemLink :href="route('logout')" as="button" method="post">
                  {{ $t('app.user_menu.logout') }}
                </MenuItemLink>
              </template>
            </DropdownMenu>
          </div>
        </div>

        <!-- Desktop navigation -->
        <nav class="hidden sm:flex sm:space-x-2">
          <Link
            v-for="link in links"
            :key="link.name"
            :class="$page.url.startsWith(link.route)
            ? 'border-violet-500 dark:border-violet-600 text-zinc-900 dark:text-white'
            : 'border-transparent transition-all ease-in-out hover:text-zinc-900 dark:hover:text-white'"
            :href="link.href"
            class="group inline-flex items-center border-b-2 text-sm font-medium"
          >
            <span
              class="px-4 py-2 mb-1 rounded transition-all ease-in-out group-hover:bg-zinc-100 dark:group-hover:bg-zinc-800"
            >
              {{ link.name }}
            </span>
          </Link>
        </nav>
      </div>
    </div>
  </header>

  <main class="py-10 space-y-10">
    <slot />
  </main>

  <FlashNotification />
</template>

<script lang="ts" setup>
import { MenuButton, Popover, PopoverButton, PopoverOverlay, PopoverPanel } from '@headlessui/vue'
import { Bars3Icon } from '@heroicons/vue/24/outline'
import BlastLogo from '@/Components/Logo/BlastLogo.vue'
import { Link, usePage } from '@inertiajs/vue3'
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue'
import MenuItemLink from '@/Components/Dropdown/MenuItemLink.vue'
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { trans, transChoice } from 'laravel-vue-i18n'
import { computed } from 'vue'
import { PageProps, User } from '@/types'
import FlashNotification from '@/Components/Notifications/FlashMessages.vue'

const user = computed<User>(() => {
  return usePage<PageProps<{}>>().props.user
})

interface NavLink {
  name: string
  href: string
  route: string
}

const links = computed<NavLink[]>(() => [
  {
    name: transChoice('links.links', 2),
    href: route('links'),
    route: '/links',
  },
  {
    name: trans('common.settings'),
    href: route('account-settings'),
    route: '/settings',
  },
])
</script>
