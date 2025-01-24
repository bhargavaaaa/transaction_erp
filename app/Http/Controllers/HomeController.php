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
        $customers = Order::select('customer')->whereNotNull('customer')->groupBy('customer')->pluck('customer');
        $parts = Order::select('part_name')->whereNotNull('part_name')->groupBy('part_name')->pluck('part_name');
        $metals = Order::select('metal')->whereNotNull('metal')->groupBy('metal')->pluck('metal');

        return $dataTable->render('home', compact('customers', 'parts', 'metals'));
    }
}
