<template>
  <DropdownMenu size="xs">
    <template #button>
      <MenuButton class="focus-ring rounded-full transition-all ease-in-out">
        <span class="sr-only">{{ $t('Open menu') }}</span>
        <PlaceholderAvatar :initials="user.initials" />
      </MenuButton>
    </template>

    <template #menuItems>
      <MenuItemLink :href="route('account-settings.show')">
        {{ $t('Account settings') }}
      </MenuItemLink>
      <MenuItemLink :href="route('logout')" as="button" method="post">
        {{ $t('Logout') }}
      </MenuItemLink>
    </template>
  </DropdownMenu>
</template>

<script lang="ts" setup>
import MenuItemLink from '@/Components/Dropdown/MenuItemLink.vue'
import { MenuButton } from '@headlessui/vue'
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue'
import PlaceholderAvatar from '@/Components/Avatars/PlaceholderAvatar.vue'
import { CurrentUser } from '@/types/models'

interface Props {
  user: CurrentUser
}

defineProps<Props>()
</script>
