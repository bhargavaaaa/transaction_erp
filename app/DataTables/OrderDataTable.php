<?php

namespace App\DataTables;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->toDateTimeLocalString();
            })
            ->editColumn('updated_at', function ($data) {
                return Carbon::parse($data->updated_at)->toDateTimeLocalString();
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="btn-group">';
                $html .= getEditButton(route('order.edit', ['order' => $data->id]), "", "", "order-edit");
                $html .= getDeleteButton("javascript:;", "deleteModel", "data-id=\"$data->id\"", "order-delete");
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->with('createdBy:id,name', 'updatedBy:id,name')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-table')
            ->addTableClass(['table-striped', 'table-nowrap', 'datatable'])
            ->lengthMenu([100, 200, 500, 1000])
            ->minifiedAjax()
            ->searching()
            ->serverSide()
            ->scrollX()
            ->dom("<'row'<'col-sm-12 col-md-6 mb-2'l><'col-sm-12 col-md-6 mb-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
            ->columns($this->getColumns());
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->orderable(false)
                ->searchable(false)
                ->title('#'),

            Column::make('work_order_number')
                ->title('PC No'),

            Column::make('customer')
                ->title('Cust'),

            Column::make('part_name')
                ->title('Part Name'),

            Column::make('metal')
                ->title('Metal'),

            Column::make('size')
                ->title('Size'),

            Column::make('quantity')
                ->title('Qty'),

            Column::make('required_weight')
                ->title('Req. Wt'),

            Column::make('delivery_date')
                ->title('Del Dt'),

            Column::make('created_by.name', 'createdBy.name')
                ->searchable(false)
                ->title('Created By'),

            Column::make('updated_by.name', 'updatedBy.name')
                ->searchable(false)
                ->title('Updated By'),

            Column::make('created_at')
                ->title('Created At'),

            Column::make('updated_at')
                ->title('Updated At'),

            Column::make('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
