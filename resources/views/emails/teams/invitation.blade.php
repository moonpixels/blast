@component('mail::message')
  # {{ __('Team Invitation') }}

  {{ __('You have been invited to join the **:team** team on :app_name! You can accept the invitation by clicking the button below.', ['team' => $invitation->team->name, 'app_name' => config('app.name')]) }}

  @component('mail::panel')
    {!! __('You\'ll need to have an account before you can accept the invitation. You can sign up for a free account <a href=":url" target="_blank" rel="noopener">here</a>.', ['url' => route('register')]) !!}
  @endcomponent

  @component('mail::button', ['url' => $invitation->accept_url])
    {{ __('Accept invitation') }}
  @endcomponent

  {{ __('If you weren\'t expecting to receive an invitation to this team, you can ignore this email.') }}
@endcomponent
