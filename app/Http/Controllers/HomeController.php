<?php

namespace App\Http\Controllers;

use App\DataTables\GanttDataTable;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:gantt-view')->except([]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GanttDataTable $dataTable)
    {
        $customers = Order::selectRaw('TRIM(customer) as customer')->whereNotNull('customer')->groupBy('customer')->pluck('customer');
        $metals = Order::selectRaw('TRIM(metal) as metal')->whereNotNull('metal')->groupBy('metal')->pluck('metal');

        return $dataTable->render('home', compact('customers', 'metals'));
    }
}
