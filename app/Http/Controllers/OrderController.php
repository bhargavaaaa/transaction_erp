<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use App\Models\Order;
use App\Rules\UniqueItemNames;
use App\Traits\OrderAuthorizable;
use App\DataTables\OrderDataTable;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class OrderController extends Controller
{
    use OrderAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Order::selectRaw('TRIM(customer) as customer')->whereNotNull('customer')->groupBy('customer')->pluck('customer');
        $parts = Order::selectRaw('TRIM(part_name) as part_name')->whereNotNull('part_name')->groupBy('part_name')->pluck('part_name');
        $metals = Order::selectRaw('TRIM(metal) as metal')->whereNotNull('metal')->groupBy('metal')->pluck('metal');
        $sizes = Order::selectRaw('TRIM(size) as size')->whereNotNull('size')->groupBy('size')->pluck('size');

        return response()->view('order.create', compact('customers', 'parts', 'metals', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $input = $request->safe()->all();
        $input["po_date"] = Carbon::createFromFormat("d-m-Y", $request->po_date);
        $input["delivery_date"] = Carbon::createFromFormat("d-m-Y", $request->delivery_date);
        Order::create($input);

        return redirect()->route('order.index')->with('success', 'Order has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        $customers = Order::selectRaw('TRIM(customer) as customer')->whereNotNull('customer')->groupBy('customer')->pluck('customer');
        $parts = Order::selectRaw('TRIM(part_name) as part_name')->whereNotNull('part_name')->groupBy('part_name')->pluck('part_name');
        $metals = Order::selectRaw('TRIM(metal) as metal')->whereNotNull('metal')->groupBy('metal')->pluck('metal');
        $sizes = Order::selectRaw('TRIM(size) as size')->whereNotNull('size')->groupBy('size')->pluck('size');

        return response()->view('order.edit', compact('order', 'customers', 'parts', 'metals', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        $order = Order::findOrFail($id);
        $input = $request->safe()->all();
        $input["po_date"] = Carbon::createFromFormat("d-m-Y", $request->po_date);
        $input["delivery_date"] = Carbon::createFromFormat("d-m-Y", $request->delivery_date);
        $order->update($input);

        return redirect()->route('order.index')->with('success', 'Order has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        if(!isOrderOpenToDelete($order)) {
            return response()->json(["status" => false, "message" => "Transactions are found for this order, first delete transactions then delete order."]);
        }

        $order->delete();

        return response()->json(["status" => true, "message" => "Order has been deleted."]);
    }

    public function import_orders()
    {
        return view('order.import_orders');
    }

    public function import_orders_store(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);

        $rules = [
            'order_file' => 'required|mimes:xls,xlsx,csv',
        ];
        $this->validate($request, $rules);
        DB::beginTransaction();
        try {
            $orders = (new ItemsImport())->toArray($request->file('order_file'))[0];
            $rules = [
                '*' => 'required|array|min:1|max:1000',
            ];

            $validator = Validator::make($orders, $rules, ['*.min' => 'Minimum 1 order is required in order import file', '*.max' => 'Maximum 1000 order is limited in order import file']);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $errorMessage = implode(', ', $messages->all());
                return redirect()->back()->with("error", $errorMessage);
            }

            $rules = [
                '*.work_order_number' => ['required', new UniqueItemNames($orders), 'unique:orders,work_order_number'],
                '*.customer' => ['required', 'string', 'max:255'],
                '*.part_name' => ['required', 'string', 'max:255'],
                '*.metal' => ['required', 'string', 'max:255'],
                '*.size' => ['required', 'string', 'max:255'],
                '*.quantity' => ['required', 'numeric'],
                '*.weight_per_pcs' => ['required', 'numeric'],
                '*.required_weight' => ['required', 'numeric'],
                '*.po_no' => ['required'],
                '*.po_date' => ['required', 'date'],
                '*.delivery_date' => ['nullable', 'date'],
                '*.remark' => ['nullable', 'string', 'max:255']
            ];

            $validator = Validator::make($orders, $rules);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $errorMessage = implode(', ', $messages->all());
                return redirect()->back()->with("error", $errorMessage);
            }

            foreach ($orders as $i) {
                $order = new Order();

                $order->work_order_number = $i['work_order_number'] ?? NULL;
                $order->work_order_number = trim($order->work_order_number);

                $order->customer = $i['customer'] ?? NULL;
                $order->customer = trim($order->customer);

                $order->part_name = $i['part_name'] ?? NULL;
                $order->part_name = trim($order->part_name);

                $order->metal = $i['metal'] ?? NULL;
                $order->metal = trim($order->metal);

                $order->size = $i['size'] ?? NULL;
                $order->size = trim($order->size);

                $order->quantity = $i['quantity'] ?? NULL;
                $order->quantity = trim($order->quantity);

                $order->weight_per_pcs = $i['weight_per_pcs'] ?? NULL;
                $order->weight_per_pcs = trim($order->weight_per_pcs);

                $order->required_weight = $i['required_weight'] ?? NULL;
                $order->required_weight = trim($order->required_weight);

                $order->po_no = $i['po_no'] ?? NULL;
                $order->po_no = trim($order->po_no);

                $order->po_date = $i['po_date'] ?? NULL;
                $order->po_date = $order->po_date ? trim($order->po_date) : NULL;

                $order->delivery_date = $i['delivery_date'] ?? NULL;
                $order->delivery_date = $order->delivery_date ? trim($order->delivery_date) : NULL;

                $order->remark = $i['remark'] ?? NULL;
                $order->remark = trim($order->remark);

                $order->created_by = getCurrentUserId();
                $order->updated_by = getCurrentUserId();

                $order->save();
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong, ".$e->getMessage());
        }
        DB::commit();
        return redirect()->route('order.index')->with("success", count($orders)." orders have been imported.");
    }

    public function finish(string $order)
    {
        $order = Order::findOrFail($order);
        DB::beginTransaction();
        try {
            if($order->status) {
                throw new RuntimeException('Can not do edit or delete after transaction finished.');
            }

            $input["status"] = true;
            $input["updated_by"] = getCurrentUserId();
            $order->update($input);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error", "Something went wrong, " . $e->getMessage());
        }
        DB::commit();

        return redirect()->back()->with('success', 'Order has been finished.');
    }

    public function unfinish(string $order)
    {
        $order = Order::findOrFail($order);
        DB::beginTransaction();
        try {
            if(!$order->status) {
                throw new RuntimeException('Can not do edit or delete after order finished.');
            }

            $input["status"] = false;
            $input["updated_by"] = getCurrentUserId();
            $order->update($input);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error", "Something went wrong, " . $e->getMessage());
        }
        DB::commit();

        return redirect()->back()->with('success', 'Order has been unfinished.');
    }

    public function checkWorkOrderNoUnique(Request $request, string $id = null)
    {
        $work_order_number = $request->input('work_order_number');
        if(empty($id)) {
            $isUnique = Order::where('work_order_number', $work_order_number)->count() === 0;
        } else {
            $isUnique = Order::where('work_order_number', $work_order_number)->whereNot('id', $id)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }
}
