@component('mail::message')
# Order Confirmed

## Hi, {{$order->user->name}} <br>

We have recieved your Order for <br>

###Products: <br>
@foreach($order->cart['items'] as $item)
    {{ $item['item']['name'] }} ({{ $item['qty'] }}) <br>
@endforeach

###Totals: <br>
**Total Quantity: ** {{ $order->cart['totalQty'] }} <br>
**Ammount Paid: ** {{ $order->amount_charged }} <br>

@component('mail::button', ['url' => Config::get('app.url').'/dashboard', 'color' => 'green'])
View Order
@endcomponent
Your delivery should be expected in 7 days. <br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent