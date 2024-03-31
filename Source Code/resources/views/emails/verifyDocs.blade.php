@component('mail::message')
# New Store Registered

There is new Registration of a store.<br>
Store Name: {{ $name }}<br>
Email: {{ $email }}<br>

Please Verify documents attached to this mail<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
