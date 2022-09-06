@component('mail::message')
Ada User baru yang mendaftar



@component('mail::button', ['url' => 'gpttulungagung.com/login'])
Go to gpttulungagung.com
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
