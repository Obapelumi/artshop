@component('mail::message')
# Vendor Report

## Hi, {{$vendor->brand_name}}

Here's how your products are doing,
<ul>
    @foreach($vendor->product as $product)
        <li>{{$product->name}}: {{$product->quantity_sold}}</li>
    @endforeach
</ul>

    Make sure to check out our <a href="{{config('app.url')}}/blog">blog</a> for tips on how to improve your products' performance.

Cheers,<br>
{{ config('app.name') }}
@endcomponent
