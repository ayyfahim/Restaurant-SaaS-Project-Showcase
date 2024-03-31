@component('mail::message')
# Verify Your email

Click on below button to verify your email

@component('mail::button', ['url' => $link, 'color' => 'success'])
Verify
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
