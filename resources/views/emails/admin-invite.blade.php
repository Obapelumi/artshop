@component('mail::message')
# Artshop {{ $user['role'] }} invite

## Hi {{$user['user']->name}}, <br>

You have been invited to work with the **Artshop** team to as {{ $user['role'] === 'Administrator' ? 'an' : 'a'  }} **{{ $user['role'] }}** <br>

Your user name is: **{{ $user['user']->email }}** <br>
@if($user['password'])
Your password is: **{{ $user['password'] }}** <br> 
@endif
(Note: If you already had an account with artshop then use your old password to sign in)


@component('mail::button', ['url' => Config::get('app.url').'/login', 'color' => 'green'])
LOGIN
@endcomponent

## We look forward to working with you <br> 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
