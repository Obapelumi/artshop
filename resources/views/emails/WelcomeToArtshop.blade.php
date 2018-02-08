@component('mail::message')
# Welcome, {{ $user->name }}

@component('mail::panel')
	Thanks for signing up, We appreciate your patronage! <br>
	Your verification code is: **{{$user->meta->verification_code}}**
@endcomponent

@component('mail::button', ['url' => Config::get('app.url').'/verify-mail', 'color' => 'green'])
VERIFY YOUR E-MAIL ADDRESS
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent