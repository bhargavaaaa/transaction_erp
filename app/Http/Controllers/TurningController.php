<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use App\Models\Order;
use App\Traits\TurningAuthorizable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    public function import_store(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);

        $rules = [
            'file' => 'required|mimes:xls,xlsx',
        ];
        $this->validate($request, $rules);
        DB::beginTransaction();
        try {
            $transactions = (new ItemsImport())->toArray($request->file('file'))[0];
            $rules = [
                'transactions' => 'required|array|min:1|max:1000',
                'transactions.*' => 'required|array',
            ];

            $messages = [
                'transactions.min' => 'Minimum 1 transaction is required in the import file.',
                'transactions.max' => 'A maximum of 1000 transactions are allowed in the import file.',
                'transactions.*.required' => 'Each transaction must be a valid array.',
            ];

            $validator = Validator::make(['transactions' => $transactions], $rules, $messages);

            if ($validator->fails()) {
                $messages = $validator->messages();
                $errorMessage = implode(', ', $messages->all());
                return response()->json(["status" => false, "message" => $errorMessage]);
            }

            $rules = [
                '*.work_order_number' => ['required', Rule::exists('orders', 'work_order_number')->where('status', 0)],
                '*.date' => ['required', 'date'],
                '*.recorded_quantity' => ['required'],
                '*.rejected_quantity' => ['required'],
                '*.remark' => ['nullable', 'string', 'max:255']
            ];

            $validator = Validator::make($transactions, $rules);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $errorMessage = implode(', ', $messages->all());
                return response()->json(["status" => false, "message" => $errorMessage]);
            }

            foreach ($transactions as $i) {
                $order = Order::where('work_order_number', trim($i['work_order_number']))->first();

                $order->turning_end_date = $i['date'] ?? NULL;
                $order->turning_end_date = trim($order->turning_end_date);

                $order->turning_recorded_quantity = $i['recorded_quantity'] ?? NULL;
                $order->turning_recorded_quantity = trim($order->turning_recorded_quantity);

                $order->turning_rejected_quantity = $i['rejected_quantity'] ?? NULL;
                $order->turning_rejected_quantity = trim($order->turning_rejected_quantity);

                $order->turning_net_quantity = $order->turning_recorded_quantity - $order->turning_rejected_quantity;

                $order->turning_remark = $i['remark'] ?? NULL;
                $order->turning_remark = trim($order->turning_remark);

                $order->updated_by = getCurrentUserId();

                $order->save();
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, ".$e->getMessage()]);
        }
        DB::commit();
        return response()->json(["status" => true, "message" => count($transactions)." transactions have been imported."]);
    }
}
