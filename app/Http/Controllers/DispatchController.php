<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\DispatchAuthorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DispatchController extends Controller
{
    use DispatchAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->view('dispatch.index');
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
            if(empty($order->dispatch_end_date)) {
                $last_recorded_quantity = $this->getLastRecordedQuantity($order);
                return response()->json(["status" => true, "html" => view('dispatch.create', compact('order', 'last_recorded_quantity'))->render()]);
            } else {
                return response()->json(["status" => true, "html" => view('dispatch.delete', compact('order'))->render()]);
            }
        }
        return response()->json(["status" => false, "message" => "Order not found."]);
    }

    function getLastRecordedQuantity($order)
    {
        if (!empty($order->other_end_date)) {
            return $order->other_net_quantity ?? 0;
        } else if (!empty($order->milling_end_date)) {
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
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)->whereNull('dispatch_end_date')],
            'dispatch_end_date' => ['required', 'date'],
            'dispatch_recorded_quantity' => ['required', 'numeric'],
            'dispatch_rejected_quantity' => ['required', 'numeric'],
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();
        $order->dispatch_end_date = Carbon::createFromFormat('d-m-Y', $request->dispatch_end_date);
        $order->dispatch_recorded_quantity = $request->dispatch_recorded_quantity;
        $order->dispatch_rejected_quantity = $request->dispatch_rejected_quantity;
        $order->dispatch_net_quantity = $order->dispatch_recorded_quantity - $order->dispatch_rejected_quantity;
        $order->dispatch_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "html" => "Order dispatch has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order)
    {
        $order = Order::open()->whereNotNull('dispatch_end_date')->findOrFail($order);
        $order->dispatch_end_date = NULL;
        $order->dispatch_recorded_quantity = 0;
        $order->dispatch_rejected_quantity = 0;
        $order->dispatch_net_quantity = 0;
        $order->dispatch_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "message" => "Dispatch has been deleted."]);
    }
}
