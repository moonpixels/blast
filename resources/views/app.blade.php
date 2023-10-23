<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title inertia>{{ __('Blast - The privacy-focused open source URL shortener') }}</title>

  @lemonJS
  @routes
  @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
  @inertiaHead
</head>
<body class="font-sans antialiased text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-900">
@inertia
</body>
</html>
