<?php

namespace App\DataTables;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GanttDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $clone_query = clone $query;

        $total_line_items = $clone_query->count();
        $quantity_total = $clone_query->sum('quantity');
        $parts = $clone_query->selectRaw('TRIM(part_name) as id, TRIM(part_name) as text')->whereNotNull('part_name')->groupByRaw('1, 2')->pluck('text', 'id');

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('delivery_date', function ($data) {
                return Carbon::parse($data->delivery_date)->format("d/m/Y");
            })
            ->addColumn('svg_code', function ($data) {
                $array = [
                    "Cutting" => $data->cutting_end_date ? date("d/m/Y", strtotime($data->cutting_end_date))." - Q: ".$data->cutting_net_quantity : NULL,
                    "Turning" => $data->turning_end_date ? date("d/m/Y", strtotime($data->turning_end_date))." - Q: ".$data->turning_net_quantity : NULL,
                    "Milling" => $data->milling_end_date ? date("d/m/Y", strtotime($data->milling_end_date))." - Q: ".$data->milling_net_quantity : NULL,
                    "Other" => $data->other_end_date ? date("d/m/Y", strtotime($data->other_end_date))." - Q: ".$data->other_net_quantity : NULL,
                    "Dispatch" => $data->dispatch_end_date ? date("d/m/Y", strtotime($data->dispatch_end_date))." - Q: ".$data->dispatch_net_quantity : NULL,
                ];

                $completed = array_filter($array, function($value) {
                    return !empty($value);
                });

                $y = 0;

                $height = count($completed) * 320 / 5;
                $height = round($height);

                $svg = '<svg width="350" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <g id="completed">
                            <circle style="fill:#414640; stroke-width:0;" cx="10" cy="10" r="10" />
                            <line x1="10" y1="20" x2="10" y2="70" style="stroke:#414640;stroke-width:3;" />
                        </g>
                        <g id="not_complete">
                            <circle style="fill:#b1d9c6; stroke-width:0;" cx="10" cy="10" r="10" />
                            <line x1="10" y1="20" x2="10" y2="70" style="stroke:#b1d9c6;stroke-width:3;" />
                        </g>
                        <g id="final_dot_complete">
                            <circle style="fill:#414640; stroke-width:0;" cx="10" cy="10" r="10" />
                        </g>
                        <g id="final_dot_not_complete">
                            <circle style="fill:#b1d9c6; stroke-width:0;" cx="10" cy="10" r="10" />
                        </g>
                    </defs>';

                foreach ($completed as $key => $value) {
                    if (array_key_last($completed) == $key) {
                        $status = "final_dot_complete";
                    } else {
                        $status = "completed";
                    }
                    $svg .= "<use x='0' y='{$y}' xlink:href='#{$status}' />";
                    $y += 70;
                }

                $y = 16;
                foreach ($completed as $key => $value) {
                    $svg .= "<text x='30' y='{$y}' font-family='Arial' font-size='12' font-weight='600' fill='black'>{$key} {$value}</text>";
                    $y += 70;
                }

                $svg .= '</svg>';

                return $svg;
            })
            ->editColumn('po_date', function ($data) {
                return Carbon::parse($data->po_date)->format("d/m/Y");
            })
            ->addColumn('row_color', function ($data) {
                if ($data->dispatch_net_quantity >= $data->quantity) {
                    return "#bfebb7";
                } else if ($data->delivery_date < Carbon::now()) {
                    return "#f7b2b2";
                } else {
                    return "#ffffff";
                }
            })
            ->addColumn('total_line_items', function () use ($total_line_items) {
                return "Total Items: $total_line_items";
            })
            ->addColumn('quantity_total', function () use ($quantity_total) {
                return $quantity_total;
            })
            ->addColumn('parts', function () use ($parts) {
                return json_encode($parts);
            })
            ->addColumn('set_part_data', function () use ($parts) {
                return trim(request('part_name') ?? "");
            })
            ->rawColumns(['svg_code', 'parts']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model
            ->when(!empty(request('customer')), function ($query) {
                $query->whereRaw('TRIM(customer) = "'.trim(request('customer')).'"');
            })
            ->when(!empty(request('part_name')), function ($query) {
                $query->whereRaw('TRIM(part_name) = "'.trim(request('part_name')).'"');
            })
            ->when(!empty(request('metal')), function ($query) {
                $query->whereRaw('TRIM(metal) = "'.trim(request('metal')).'"');
            })
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-table')
            ->addTableClass(['table-nowrap', 'datatable'])
            ->lengthMenu([100, 200, 500, 1000])
            ->minifiedAjax('', null, ["customer" => "$('#customer').val()", "part_name" => "$('#part_name').val()", "metal" => "$('#metal').val()"])
            ->searching()
            ->serverSide()
            ->scrollX()
            ->ordering(false)
            ->dom("<'row'<'col-sm-12 col-md-6 mb-2'l><'col-sm-12 col-md-6 mb-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
            ->createdRow('function(row, data, dataIndex) {
                $(row).find("td").css("background-color", data.row_color).css("cursor", "pointer");
            }')
            ->footerCallback('function (row, data, start, end, display) {
                let api = this.api();

                let total_line_items = (data && data[0] && data[0].total_line_items) ? data[0].total_line_items : 0;
                let set_part_data = (data && data[0] && data[0].set_part_data) ? data[0].set_part_data : 0;
                let quantity_total = (data && data[0] && data[0].quantity_total) ? data[0].quantity_total : 0;
                let parts = (data && data[0] && data[0].parts) ? JSON.parse(data[0].parts) : [];

                $("#total_line_items").text(total_line_items);
                $("#total_quantity").text(quantity_total);
                addSelectData($("#part_name"), parts);
                $("#part_name").val(set_part_data).trigger("change");
            }')
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

            Column::make('po_date')
                ->title('PO Date'),

            Column::make('po_no')
                ->title('PO No'),

            Column::make('delivery_date')
                ->title('Del Dt'),

            Column::make('part_name')
                ->title('Part Name'),

            Column::make('metal')
                ->title('Metal'),

            Column::make('quantity')
                ->title('Qty'),

            Column::make('cutting_net_quantity')
                ->title('C'),

            Column::make('turning_net_quantity')
                ->title('T'),

            Column::make('milling_net_quantity')
                ->title('M'),

            Column::make('other_net_quantity')
                ->title('O'),

            Column::make('dispatch_net_quantity')
                ->title('D'),
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
