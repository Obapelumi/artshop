@component('mail::message')
# Password Reset

Hi, {{$user->name}}
Your new password is **{{$user->password}}**

@component('mail::button', ['url' => Config::get('app.url').'/login', 'color' => 'green'])
LOGIN
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
