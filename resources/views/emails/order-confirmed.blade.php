@component('mail::message')
# Order Confirmed

## Hi, {{$order->user->name}} <br>

We have recieved your Order<br><br>

### Items: <br>
@foreach($order->cart->cart['items'] as $item)
    {{ $item['item']['name'] }} ({{ $item['qty'] }}),

@endforeach

###Totals: <br>
**Total Quantity: ** {{ $order->cart->cart['totalQty'] }} <br>
**Ammount Paid: ** &#8358;{{ $order->amount_charged }} <br>

@component('mail::button', ['url' => Config::get('app.url').'/dashboard', 'color' => 'green'])
View Order
@endcomponent
Your delivery should be expected in 7 days. <br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent