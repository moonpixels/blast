<template>
  <DropdownMenu size="xs">
    <template #button>
      <MenuButton class="focus-ring rounded-full transition-all ease-in-out">
        <span class="sr-only">{{ $t('Open menu') }}</span>
        <BaseAvatar :initials="user.initials" />
      </MenuButton>
    </template>

    <template #menuItems>
      <DropdownMenuItemLink :href="route('user.edit')">
        {{ $t('Account settings') }}
      </DropdownMenuItemLink>
      <DropdownMenuItemLink :href="route('logout')" as="button" method="post">
        {{ $t('Logout') }}
      </DropdownMenuItemLink>
    </template>
  </DropdownMenu>
</template>

<script lang="ts" setup>
import DropdownMenuItemLink from '@/components/DropdownMenuItemLink.vue'
import { MenuButton } from '@headlessui/vue'
import DropdownMenu from '@/components/DropdownMenu.vue'
import BaseAvatar from '@/components/BaseAvatar.vue'
import { User } from '@/types/models'

type Props = {
  user: User
}

defineProps<Props>()
</script>
