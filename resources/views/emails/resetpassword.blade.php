@component('mail::message')
Reset Your Password



@component('mail::button', ['url' => $uri])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
