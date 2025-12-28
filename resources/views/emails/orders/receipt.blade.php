<x-mail::message>
    # Order Receipt

    Hi {{ $order->user->first_name }},

    Thank you for your order!

    ## Order Details
    **Order ID:** #{{ $order->id }}
    **Total:** ${{ number_format($order->total_amount, 2) }}

    We will notify you when your items are shipped.

    <x-mail::button :url="url('/dashboard/orders/' . $order->id)">
        View Order
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>