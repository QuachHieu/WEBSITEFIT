<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'orders';
        $this->viewPart = 'admin.pages.orders';
        $this->responseData['module_name'] = __('Order Management');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|max:255'
        ]);

        $params = $request->only([
            'status', 'admin_note'
        ]);

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $order->fill($params);
        $order->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listOrderService(Request $request)
    {
        $this->responseData['module_name'] = __('Service booking Management');

        $params = $request->all();
        $this->responseData['params'] = $params;
        $params['is_type'] = Consts::ORDER_TYPE['service'];
        if (isset($params['created_at_from'])) {
            $params['created_at_from'] = Carbon::createFromFormat('d/m/Y', $params['created_at_from'])->format('Y-m-d');
        }
        if (isset($params['created_at_to'])) {
            $params['created_at_to'] = Carbon::createFromFormat('d/m/Y', $params['created_at_to'])->addDays(1)->format('Y-m-d');
        }
        $rows = ContentService::getOrderService($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.order_service_list');
    }

    public function showOrderService(Order $order)
    {
        $this->responseData['module_name'] = __('Service booking Management');

        $params['id'] = $order->id;
        $this->responseData['detail'] = ContentService::getOrderService($params)->first();

        return $this->responseView($this->viewPart . '.order_service_show');
    }

    public function listOrderProduct(Request $request)
    {
        $this->responseData['module_name'] = __('Order Product Management');

        $params = $request->all();
        $this->responseData['params'] = $params;
        $params['is_type'] = Consts::ORDER_TYPE['product'];
        if (isset($params['created_at_from'])) {
            $params['created_at_from'] = Carbon::createFromFormat('d/m/Y', $params['created_at_from'])->format('Y-m-d');
        }
        if (isset($params['created_at_to'])) {
            $params['created_at_to'] = Carbon::createFromFormat('d/m/Y', $params['created_at_to'])->addDays(1)->format('Y-m-d');
        }
        $rows = ContentService::getOrderProduct($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.order_product_list');
    }

    public function showOrderProduct(Order $order)
    {
        $this->responseData['module_name'] = __('Order Product Management');
        $this->responseData['detail'] = $order;

        $params['order_id'] = $order->id;
        $this->responseData['rows'] = ContentService::getOrderDetail($params)->get();

        return $this->responseView($this->viewPart . '.order_product_show');
    }
}
