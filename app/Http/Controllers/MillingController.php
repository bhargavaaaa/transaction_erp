<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\MillingAuthorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MillingController extends Controller
{
    use MillingAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->view('milling.index');
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
            if(empty($order->milling_end_date)) {
                $last_recorded_quantity = $this->getLastRecordedQuantity($order);
                return response()->json(["status" => true, "html" => view('milling.create', compact('order', 'last_recorded_quantity'))->render()]);
            } else {
                return response()->json(["status" => true, "html" => view('milling.delete', compact('order'))->render()]);
            }
        }
        return response()->json(["status" => false, "message" => "Order not found."]);
    }

    function getLastRecordedQuantity($order)
    {
        if (!empty($order->turning_end_date)) {
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
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)->whereNull('milling_end_date')],
            'milling_end_date' => ['required', 'date'],
            'milling_recorded_quantity' => ['required', 'numeric'],
            'milling_rejected_quantity' => ['required', 'numeric'],
            'milling_remark' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();
        $order->milling_end_date = Carbon::createFromFormat('d-m-Y', $request->milling_end_date);
        $order->milling_recorded_quantity = $request->milling_recorded_quantity;
        $order->milling_rejected_quantity = $request->milling_rejected_quantity;
        $order->milling_net_quantity = $order->milling_recorded_quantity - $order->milling_rejected_quantity;
        $order->milling_remark = $request->milling_remark;
        $order->milling_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "html" => "Order milling has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order)
    {
        $order = Order::open()->whereNotNull('milling_end_date')->findOrFail($order);
        $order->milling_end_date = NULL;
        $order->milling_recorded_quantity = 0;
        $order->milling_rejected_quantity = 0;
        $order->milling_net_quantity = 0;
        $order->milling_remark = NULL;
        $order->milling_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "message" => "Milling has been deleted."]);
    }
}
