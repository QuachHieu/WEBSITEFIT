<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Models\CmsProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrderService(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'customer_note' => "required|string",
                'item_id' => "required|integer|min:0",
            ]);
            // Check and store order
            $order_params = $request->only([
                'name', 'email', 'phone', 'customer_note'
            ]);
            $order_params['is_type'] = Consts::ORDER_TYPE['service'];
            $order = Order::create($order_params);

            // Check and store order_detail
            $order_detail_params = $request->only([
                'item_id', 'quantity', 'price', 'discount'
            ]);
            $order_detail_params['quantity'] = $request->get('quantity') > 0 ? $request->get('quantity') : 1;
            $order_detail_params['order_id'] = $order->id;
            $order_detail_params['json_params']['post_type'] = Consts::POST_TYPE['service'];
            $order_detail_params['json_params']['post_link'] = $request->headers->get('referer');

            $order_detail = OrderDetail::create($order_detail_params);

            $messageResult = $this->web_information->information->notice_advise ?? __('Booking successfull!');

            if (isset($this->web_information->information->email)) {
                $email = $this->web_information->information->email;
                Mail::send(
                    'frontend.emails.booking',
                    [
                        'order' => $order,
                        'order_detail' => $order_detail
                    ],
                    function ($message) use ($email) {
                        $message->to($email);
                        $message->subject(__('You received a new appointment from the system'));
                    }
                );
            }
            DB::commit();
            return $this->sendResponse($order, $messageResult);
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    // Cart
    public function cart()
    {
        return $this->responseView('frontend.pages.cart.index');
    }

    public function addToCart(Request $request)
    {
        $id = $request->get('id')  ?? null;
        $quantity = $request->get('quantity')  ?? 1;

        $product = CmsProduct::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] + $quantity;
        } else {
            $cart[$id] = [
                "title" => $product->title,
                "quantity" => $quantity,
                "price" => $product->json_params->price ?? null,
                "image" => $product->image,
                "image_thumb" => $product->image_thumb
            ];
        }
        session()->put('cart', $cart);
        session()->flash('successMessage', 'Product added to cart successfully!');
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('successMessage', 'Cart updated successfully!');
        }
    }

    public function removeCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('successMessage', 'Product removed successfully!');
        }
    }

    public function storeOrderProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('errorMessage', __('Cart is empty!'));
            }

            $request->validate([
                'name' => 'required',
                'phone' => 'required'
            ]);
            // Check and store order
            $order_params = $request->only([
                'name',
                'email',
                'phone',
                'address',
                'customer_note'
            ]);
            $order_params['is_type'] = Consts::ORDER_TYPE['product'];
            $order = Order::create($order_params);

            $data = [];
            foreach ($cart as $id => $details) {
                // Check and store order_detail
                $order_detail_params['order_id'] = $order->id;
                $order_detail_params['item_id'] = $id;
                $order_detail_params['quantity'] = $details['quantity'] ?? 1;
                $order_detail_params['price'] = $details['price'] ?? null;
                array_push($data, $order_detail_params);
            }
            OrderDetail::insert($data);

            $messageResult = $this->web_information->information->notice_order_cart ?? __('Submit order successfull!');

            if (isset($this->web_information->information->email)) {
                $email = $this->web_information->information->email;
                Mail::send(
                    'frontend.emails.order',
                    [
                        'order' => $order
                    ],
                    function ($message) use ($email) {
                        $message->to($email);
                        $message->subject(__('You received a new order from the system'));
                    }
                );
            }
            DB::commit();
            session()->forget('cart');
            return redirect()->back()->with('successMessage', $messageResult);
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
