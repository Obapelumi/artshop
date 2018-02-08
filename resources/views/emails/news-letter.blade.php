@component('mail::message')
# ARTSHOP NEWSLETTER

## {{$newsletter->title}}
<br>

{{$newsletter->body}}

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{$newsletter->user->name}} from {{ config('app.name') }}
@endcomponent
