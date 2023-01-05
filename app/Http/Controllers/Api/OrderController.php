<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    
    /**
     * 
     */
    public function checkout(Request $request){

        $request->validate([
            "product" => "required|int|exists:products,id"
        ]);

        $product = Product::find($request->product);


        $lineItems = [
            [
                "price_data" => [
                    "currency" => "usd",
                    "product_data" => [
                        "name" => $product->title,
                        "images" => [$product->image]
                    ],
                    'unit_amount' => $product->price * 1,
                ],
                'quantity' => 1,
            ]
        ];

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('api.checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $order = Order::create([
            "status" => "unpaid",
            "total_price" => $product->price,
            "session_id" => $session->id
        ]);

        return response()->json([
            "session_url" => $session->url
        ]);
    }

    /**
     * 
     */
    public function success(Request $request){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException();
            }
            $customer = \Stripe\Customer::retrieve($session->customer);

            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return view('product.checkout-success', compact('customer'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

    }

    /**
     * 
     */
    public function cancel(){

    }

    public function webHook(){

    }
}
