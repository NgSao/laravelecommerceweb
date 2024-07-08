<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\Stock;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductOrdersController extends Controller
{
    public function stripePost(Request $request)
    {
        try {
            $amount = $this->calculateOrderAmount($request->items);

            // Set your Stripe secret key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create a PaymentIntent with the amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'description' => 'Test payment from saonguyen',
            ]);

            // Return client secret to frontend
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    private function calculateOrderAmount(array $items): int
    {
        $price = 0;
        $checkoutItems = [];

        foreach ($items as $item) {
            if ($item['quantity'] > 0) {
                $checkoutItems[] = ['stock_id' => $item['stock_id'], 'quantity' => $item['quantity']];
            } else {
                abort(500);
            }
        }

        $user = JWTAuth::parseToken()->authenticate();
        $cartList = $user->cartItems()->with('stock.product')->get();

        foreach ($cartList as $cartItem) {
            foreach ($checkoutItems as $checkoutItem) {
                if ($cartItem->stock_id == $checkoutItem['stock_id']) {
                    $price += $cartItem->stock->product->price * $checkoutItem['quantity'];
                }
            }
        }

        return $price * 100; // Convert to cents
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $note = $request->note;

        foreach ($request->items as $item) {
            Order::create([
                'user_id' => $user->id,
                'stock_id' => $item['stock_id'],
                'quantity' => $item['quantity'],
                'note' => $note,
                'status' => 'pending'
            ]);

            Stock::findOrFail($item['stock_id'])->decrement('quantity', $item['quantity']);
            $user->cartItems()->where('stock_id', $item['stock_id'])->delete();
        }

        return response()->json(['message' => 'Order processed successfully']);
    }


}
