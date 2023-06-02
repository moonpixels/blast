<template>
  <DropdownMenu size="xs">
    <template #button>
      <MenuButton
        class="flex rounded-full bg-white text-sm transition-all ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white dark:bg-zinc-950 dark:focus:ring-violet-600 dark:focus:ring-offset-zinc-950"
      >
        <span class="sr-only">{{ $t('common.open_menu') }}</span>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-zinc-400 dark:bg-zinc-500">
          <span class="text-sm font-medium leading-none text-white">
            {{ user.initials }}
          </span>
        </span>
      </MenuButton>
    </template>

    <template #menuItems>
      <MenuItemLink :href="route('account-settings.show')">
        {{ $t('app.user_menu.account_settings') }}
      </MenuItemLink>
      <MenuItemLink :href="route('logout')" as="button" method="post">
        {{ $t('app.user_menu.logout') }}
      </MenuItemLink>
    </template>
  </DropdownMenu>
</template>

<script lang="ts" setup>
import MenuItemLink from '@/Components/Dropdown/MenuItemLink.vue'
import { MenuButton } from '@headlessui/vue'
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue'
import { User } from '@/types'

interface Props {
  user: User
}

defineProps<Props>()
</script>
