@component('mail::message')
  # {{ __('Verify Email') }}

  {{ __('Welcome to Blast! To protect our platform from spam, we require you to verify your email address.') }}

  {{ __('Please click the button below to verify your email.') }}

  @component('mail::button', ['url' => $url])
    {{ __('Verify email') }}
  @endcomponent

  {{ __('If you did not create an account, no further action is required and you can discard this email.') }}
@endcomponent
