<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\CuttingAuthorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CuttingController extends Controller
{
    use CuttingAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->view('cutting.index');
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
            if(empty($order->cutting_end_date)) {
                $last_recorded_quantity = $this->getLastRecordedQuantity($order);
                return response()->json(["status" => true, "html" => view('cutting.create', compact('order', 'last_recorded_quantity'))->render()]);
            } else {
                return response()->json(["status" => true, "html" => view('cutting.delete', compact('order'))->render()]);
            }
        }
        return response()->json(["status" => false, "message" => "Order not found."]);
    }

    function getLastRecordedQuantity($order)
    {
        return $order->quantity ?? 0;
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)->whereNull('cutting_end_date')],
            'cutting_end_date' => ['required', 'date'],
            'cutting_recorded_quantity' => ['required', 'numeric'],
            'cutting_rejected_quantity' => ['required', 'numeric'],
        ]);

        $order = Order::open()->where('work_order_number', $request->work_order_number)->first();
        $order->cutting_end_date = Carbon::createFromFormat('d-m-Y', $request->cutting_end_date);
        $order->cutting_recorded_quantity = $request->cutting_recorded_quantity;
        $order->cutting_rejected_quantity = $request->cutting_rejected_quantity;
        $order->cutting_net_quantity = $order->cutting_recorded_quantity - $order->cutting_rejected_quantity;
        $order->cutting_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "html" => "Order cutting has been updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order)
    {
        $order = Order::open()->whereNotNull('cutting_end_date')->findOrFail($order);
        $order->cutting_end_date = NULL;
        $order->cutting_recorded_quantity = 0;
        $order->cutting_rejected_quantity = 0;
        $order->cutting_net_quantity = 0;
        $order->cutting_updated_by = auth()->user()->id;
        $order->save();

        return response()->json(["status" => true, "message" => "Cutting has been deleted."]);
    }
}
