<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\TurningAuthorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TurningController extends Controller
{
    use TurningAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->view('turning.index');
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
            if(empty($order->turning_end_date)) {
                $last_recorded_quantity = $this->getLastRecordedQuantity($order);
                return response()->json(["status" => true, "html" => view('turning.create', compact('order', 'last_recorded_quantity'))->render()]);
            } else {
                return response()->json(["status" => true, "html" => view('turning.delete', compact('order'))->render()]);
            }
        }
        return response()->json(["status" => false, "message" => "Order not found."]);
    }

    function getLastRecordedQuantity($order)
    {
        if (!empty($order->cutting_end_date)) {
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
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)->whereNull('turning_end_date')],
            'turning_end_date' => ['required', 'date'],
            'turning_recorded_quantity' => ['required', 'numeric'],
            'turning_rejected_quantity' => ['required', 'numeric'],
            'turning_remark' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();
        $order->turning_end_date = Carbon::createFromFormat('d-m-Y', $request->turning_end_date);
        $order->turning_recorded_quantity = $request->turning_recorded_quantity;
        $order->turning_rejected_quantity = $request->turning_rejected_quantity;
        $order->turning_net_quantity = $order->turning_recorded_quantity - $order->turning_rejected_quantity;
        $order->turning_remark = $request->turning_remark;
        $order->turning_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "html" => "Order turning has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order)
    {
        $order = Order::open()->whereNotNull('turning_end_date')->findOrFail($order);
        $order->turning_end_date = NULL;
        $order->turning_recorded_quantity = 0;
        $order->turning_rejected_quantity = 0;
        $order->turning_net_quantity = 0;
        $order->turning_remark = NULL;
        $order->turning_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "message" => "Turning has been deleted."]);
    }
}
