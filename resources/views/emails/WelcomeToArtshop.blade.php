@component('mail::message')
# Welcome, {{ $user->name }}

<br>
	Thanks for signing up, We appreciate your patronage! <br>
	Your verification code is: **{{$user->meta->verification_code}}**
<br><br>
@component('mail::button', ['url' => Config::get('app.url').'/verify-mail', 'color' => 'green'])
VERIFY E-MAIL
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent