@component('mail::message')
# Your verification code

To continue, please enter this 4-digit code:

@component('mail::panel')
{{ $code }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
