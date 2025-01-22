<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\OtherAuthorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OtherController extends Controller
{
    use OtherAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->view('other.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)]
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();

        if(!empty($order)) {
            if(empty($order->other_end_date)) {
                $last_recorded_quantity = $this->getLastRecordedQuantity($order);
                return response()->json(["status" => true, "html" => view('other.create', compact('order', 'last_recorded_quantity'))->render()]);
            } else {
                return response()->json(["status" => true, "html" => view('other.delete', compact('order'))->render()]);
            }
        }
        return response()->json(["status" => false, "message" => "Order not found."]);
    }

    function getLastRecordedQuantity($order)
    {
        if (!empty($order->milling_end_date)) {
            return $order->milling_net_quantity ?? 0;
        } else if (!empty($order->turning_end_date)) {
            return $order->turning_net_quantity ?? 0;
        } else if (!empty($order->cutting_end_date)) {
            return $order->cutting_net_quantity ?? 0;
        } else {
            return $order->quantity ?? 0;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)->whereNull('other_end_date')],
            'other_end_date' => ['required', 'date'],
            'other_recorded_quantity' => ['required', 'numeric'],
            'other_rejected_quantity' => ['required', 'numeric'],
            'other_remark' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();
        $order->other_end_date = Carbon::createFromFormat('d-m-Y', $request->other_end_date);
        $order->other_recorded_quantity = $request->other_recorded_quantity;
        $order->other_rejected_quantity = $request->other_rejected_quantity;
        $order->other_net_quantity = $order->other_recorded_quantity - $order->other_rejected_quantity;
        $order->other_remark = $request->other_remark;
        $order->other_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "html" => "Order other has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order)
    {
        $order = Order::open()->whereNotNull('other_end_date')->findOrFail($order);
        $order->other_end_date = NULL;
        $order->other_recorded_quantity = 0;
        $order->other_rejected_quantity = 0;
        $order->other_net_quantity = 0;
        $order->other_remark = NULL;
        $order->other_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "message" => "Other has been deleted."]);
    }
}
