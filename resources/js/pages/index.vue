<template>
  <AppHead />

  <div class="relative isolate px-6 pt-14 lg:px-8">
    <div class="mx-auto max-w-2xl py-32">
      <div class="hidden sm:mb-8 sm:flex sm:justify-center">
        <div
          class="relative rounded-full px-3 py-1 text-sm leading-6 text-zinc-500 ring-1 ring-white/10 hover:ring-white/20"
        >
          {{ $t('Integrate our service into your application.') }}

          <a class="font-medium text-violet-400" href="/docs">
            <span aria-hidden="true" class="absolute inset-0" />Read more <span aria-hidden="true">&rarr;</span>
          </a>
        </div>
      </div>

      <div class="text-center">
        <h1 class="text-3xl font-medium tracking-tight text-white sm:text-5xl">
          {{ $t('Privacy focused link shortening for developers') }}
        </h1>

        <p class="mx-auto mt-6 max-w-xl text-lg leading-8">
          {{
            $t(
              'Blast allows you to easily integrate link shortening into your application, while keeping your users data private.'
            )
          }}
        </p>

        <div class="mt-10 flex items-center justify-center gap-x-4">
          <Link :href="route('register')">
            <ButtonPrimary :size="'lg'" class="text-sm">
              <div class="flex items-center justify-between gap-2">
                {{ $t('Get started') }}

                <ChevronRightIcon class="-mr-2 h-4 w-4" />
              </div>
            </ButtonPrimary>
          </Link>

          <a href="/docs">
            <AppButton :size="'lg'" class="text-sm">
              <div class="flex items-center justify-between gap-2">
                {{ $t('Documentation') }}

                <ChevronRightIcon class="-mr-2 h-4 w-4" />
              </div>
            </AppButton>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="overflow-hidden py-24 sm:py-32">
    <AppContainer>
      <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
        <div class="lg:pr-8 lg:pt-4">
          <div class="lg:max-w-lg">
            <h2 class="mt-2 text-3xl font-medium tracking-tight text-white sm:text-4xl">
              {{ $t('Integrate Blast into your application.') }}
            </h2>
            <p class="mt-6 text-lg leading-8">
              {{
                $t(
                  'We take a fresh approach to URL shortening. Our service redirects your users at lightning speeds, without collecting personally identifiable information or tracking their web activities.'
                )
              }}
            </p>
            <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 lg:max-w-none">
              <div v-for="feature in features" :key="feature.name" class="relative pl-9">
                <dt class="inline font-semibold text-white">
                  <component
                    :is="feature.icon"
                    aria-hidden="true"
                    class="absolute left-1 top-1 h-5 w-5 text-violet-400"
                  />
                  {{ feature.name }}
                </dt>
                {{ ' ' }}
                <dd class="inline">{{ feature.description }}</dd>
              </div>
            </dl>
          </div>
        </div>
        <img
          alt="Blast application screenshot"
          class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-white/10 sm:w-[57rem] md:-ml-4 lg:-ml-0"
          height="1442"
          src="/images/blast-app-screenshot.png"
          width="2432"
        />
      </div>
    </AppContainer>
  </div>

  <div class="py-24 sm:py-32">
    <AppContainer>
      <div class="mx-auto max-w-2xl lg:mx-0">
        <h2 class="mt-2 text-3xl font-medium tracking-tight text-white sm:text-4xl">
          {{ $t('Features you will love.') }}
        </h2>

        <p class="mt-6 text-lg leading-8">
          {{
            $t(
              'Blast provides a simple API and a beautiful dashboard to help you manage your links. Here are some of the features you will love.'
            )
          }}
        </p>
      </div>
      <dl
        class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-4 gap-y-8 text-sm leading-7 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3"
      >
        <div v-for="feature in additionalFeatures" :key="feature.name">
          <dt class="flex items-center gap-1.5 font-semibold text-white">
            <component :is="feature.icon" aria-hidden="true" class="h-3.5 w-3.5" />

            {{ feature.name }}
          </dt>
          <dd class="mt-1">{{ feature.description }}</dd>
        </div>
      </dl>
    </AppContainer>
  </div>

  <AppContainer class="pt-24 sm:pt-32">
    <div
      class="relative isolate overflow-hidden rounded-3xl bg-zinc-800 px-6 pt-16 shadow-2xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0"
    >
      <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
        <h2 class="text-3xl font-medium tracking-tight text-white sm:text-4xl">
          {{ $t('Start using Blast for free today.') }}
        </h2>

        <p class="mt-6 text-lg leading-8 text-zinc-300">
          {{ $t("It's free to get started with Blast, with a generous free tier and no credit card required.") }}
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
          <Link :href="route('register')">
            <ButtonPrimary :size="'lg'" class="text-sm">
              <div class="flex items-center justify-between gap-2">
                {{ $t('Get started') }}

                <ChevronRightIcon class="-mr-2 h-4 w-4" />
              </div>
            </ButtonPrimary>
          </Link>
        </div>
      </div>

      <div class="relative mt-16 h-80 lg:mt-8">
        <img
          alt="Blast application screenshot"
          class="absolute left-0 top-0 w-[57rem] max-w-none rounded-xl shadow-xl ring-1 ring-white/10"
          height="1442"
          src="/images/blast-app-screenshot-alt.png"
          width="2432"
        />
      </div>
    </div>
  </AppContainer>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import ButtonPrimary from '@/components/ButtonPrimary.vue'
import AppButton from '@/components/AppButton.vue'
import {
  ArrowTrendingUpIcon,
  BookOpenIcon,
  ChevronRightIcon,
  ClockIcon,
  CursorArrowRaysIcon,
  EyeSlashIcon,
  HandRaisedIcon,
  KeyIcon,
  MagnifyingGlassIcon,
  PencilIcon,
  RocketLaunchIcon,
  TagIcon,
  UserGroupIcon,
} from '@heroicons/vue/20/solid'
import AppContainer from '@/components/AppContainer.vue'
import {
  CodeBracketSquareIcon,
  EyeSlashIcon as EyeSlashOutlineIcon,
  RectangleGroupIcon,
} from '@heroicons/vue/24/outline'
import GuestLayout from '@/layouts/GuestLayout.vue'
import AppHead from '@/components/AppHead.vue'
import { FunctionalComponent } from 'vue'

defineOptions({
  layout: GuestLayout,
})

type Feature = {
  name: string
  description: string
  icon: FunctionalComponent
}

const features: Feature[] = [
  {
    name: 'Privacy by default.',
    description:
      "We don't collect any personally identifiable information about your users, or track their web activities.",
    icon: EyeSlashOutlineIcon,
  },
  {
    name: 'Developer friendly.',
    description: 'We provide a simple API that you can integrate into your application in minutes.',
    icon: CodeBracketSquareIcon,
  },
  {
    name: 'Beautiful dashboard.',
    description:
      'Manage your links with ease using our beautiful dashboard. We provide a simple and intuitive user interface.',
    icon: RectangleGroupIcon,
  },
]

const additionalFeatures: Feature[] = [
  {
    name: 'Unlimited links.',
    description:
      'Create as many links as you need. We do not limit the number of links you can create, or the number of redirects.',
    icon: ArrowTrendingUpIcon,
  },
  {
    name: 'Custom aliases.',
    description:
      'Personalise your links by choosing a custom alias. You can also choose to use a random alias if you prefer.',
    icon: TagIcon,
  },
  {
    name: 'Passwords.',
    description:
      'Protect your links with a password. Your users will be required to enter a password before they can be redirected.',
    icon: KeyIcon,
  },
  {
    name: 'Expiry dates.',
    description:
      'Set an expiry date for your links. After the expiry date has passed, your links will no longer redirect.',
    icon: ClockIcon,
  },
  {
    name: 'Visit limits.',
    description:
      'Set a visit limit for your links. After the visit limit has been reached, your links will no longer redirect.',
    icon: CursorArrowRaysIcon,
  },
  {
    name: 'Update links.',
    description:
      'Need to change the destination of your link? No problem. You can update the destination of your links at any time.',
    icon: PencilIcon,
  },
  {
    name: 'Teams.',
    description:
      'Group your links into teams and invite others to collaborate with you. You can have as many teams as you need.',
    icon: UserGroupIcon,
  },
  {
    name: 'Link searching.',
    description: 'Powerful search functionality allows you to quickly find the link you are looking for.',
    icon: MagnifyingGlassIcon,
  },
  {
    name: 'High rate limits.',
    description: 'A generous free tier with a rate limit of 60 requests per minute, upgradable with our paid plans.',
    icon: HandRaisedIcon,
  },
  {
    name: 'Great documentation.',
    description: 'Easy to use API documentation to help you get started quickly.',
    icon: BookOpenIcon,
  },
  {
    name: 'Privacy focused.',
    description:
      'We do not collect any personally identifiable information about your users or track their web activity.',
    icon: EyeSlashIcon,
  },
  {
    name: 'Amazingly fast.',
    description:
      'We use a global infrastructure of servers and edge caching to redirect your users at lightning speeds.',
    icon: RocketLaunchIcon,
  },
]
</script>
