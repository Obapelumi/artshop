@component('mail::message')
# {{$product->name}} was purchased

## Hi, {{$product->vendor->brand_name}} <br>

A purchase has been made for your product: **{{$product->name}}**

Please confirm that it is still available. <br>

@component('mail::button', ['url' => '', 'color' => 'green'])
Confirm
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
