<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ShippingService;

class CartController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index(Request $request)
    {
        // Mocking Cart Items and Subtotal for demonstration
        // In a real app, this would come from the database or session
        $cartItems = [
            ['id' => 1, 'name' => 'Abstract #1', 'price' => 2500, 'quantity' => 1],
            ['id' => 2, 'name' => 'Sculpture A', 'price' => 1000, 'quantity' => 1],
        ];

        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Calculate Shipping
        $shipping = $this->shippingService->calculate($subtotal);

        return Inertia::render('Shop/Cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
        ]);
    }
}
