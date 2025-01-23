<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderProcessCardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:order-process-card-view')->except([]);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'work_order_number' => ['required', Rule::exists('orders', 'work_order_number')]
            ]);

            $order = Order::where('work_order_number', $request->work_order_number)->first();

            return response()->json(["status" => true, "html" => view('order-process-card.part', compact('order'))->render()]);
        }

        return view('order-process-card.index');
    }
}
