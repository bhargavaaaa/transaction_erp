<tr class="all-border">
    <td colspan="6" class="text-left p-3">
        <div class="row">
            <div class="col-4">
                <p class="m-0">
                    <b>Customer Code:</b> {{ $order->customer }}<br/>
                    <b>Part Name:</b> {{ $order->part_name }}<br/>
                    <b>F. M. Size:</b> {{ $order->size }}<br/>
                    <b>Remark:</b> {{ $order->remark }}
                </p>
            </div>
            <div class="col-4">
                <p class="m-0">
                    <b>P. C. No:</b> {{ $order->work_order_number }}<br/>
                    <b>P. O. No:</b> {{ $order->po_no }}<br/>
                    <b>Metal:</b> {{ $order->metal }}<br/>
                    <b>Order Qty:</b> {{ $order->quantity }}
                </p>
            </div>
            <div class="col-4">
                <p class="m-0">
                    <b>Created Date:</b> {{ date("d/m/Y", strtotime($order->created_at)) }}<br/>
                    <b>PO Date:</b> {{ date("d/m/Y", strtotime($order->po_date)) }}<br/>
                    <b>Del. Date:</b> {{ date("d/m/Y", strtotime($order->delivery_date)) }}<br/>
                    <b>RM Wfg.:</b> {{ $order->weight_per_pcs }}
                </p>
            </div>
        </div>
    </td>
</tr>
<tr class="all-border">
    <th class="text-center all-border" rowspan="2">Operation</th>
    <th class="text-center all-border" rowspan="2">Complete Date</th>
    <th class="text-center all-border" colspan="3">Qty</th>
    <th class="text-center all-border" rowspan="2">Remark</th>
</tr>
<tr class="all-border">
    <th class="text-center all-border">Recorded</th>
    <th class="text-center all-border">Rejected</th>
    <th class="text-center all-border">Total</th>
</tr>
<tr class="all-border">
    <td class="text-center all-border">Cutting</td>
    <td class="text-center all-border">{{ $order->cutting_end_date }}</td>
    <td class="text-center all-border">{{ $order->cutting_recorded_quantity }}</td>
    <td class="text-center all-border">{{ $order->cutting_rejected_quantity }}</td>
    <td class="text-center all-border">{{ $order->cutting_net_quantity }}</td>
    <td class="text-center all-border">{{ $order->cutting_remark }}</td>
</tr>
<tr class="all-border">
    <td class="text-center all-border">Turning</td>
    <td class="text-center all-border">{{ $order->turning_end_date }}</td>
    <td class="text-center all-border">{{ $order->turning_recorded_quantity }}</td>
    <td class="text-center all-border">{{ $order->turning_rejected_quantity }}</td>
    <td class="text-center all-border">{{ $order->turning_net_quantity }}</td>
    <td class="text-center all-border">{{ $order->turning_remark }}</td>
</tr>
<tr class="all-border">
    <td class="text-center all-border">Milling</td>
    <td class="text-center all-border">{{ $order->milling_end_date }}</td>
    <td class="text-center all-border">{{ $order->milling_recorded_quantity }}</td>
    <td class="text-center all-border">{{ $order->milling_rejected_quantity }}</td>
    <td class="text-center all-border">{{ $order->milling_net_quantity }}</td>
    <td class="text-center all-border">{{ $order->milling_remark }}</td>
</tr>
<tr class="all-border">
    <td class="text-center all-border">Other Operation</td>
    <td class="text-center all-border">{{ $order->other_end_date }}</td>
    <td class="text-center all-border">{{ $order->other_recorded_quantity }}</td>
    <td class="text-center all-border">{{ $order->other_rejected_quantity }}</td>
    <td class="text-center all-border">{{ $order->other_net_quantity }}</td>
    <td class="text-center all-border">{{ $order->other_remark }}</td>
</tr>
<tr class="all-border">
    <td class="text-center all-border">Dispatch</td>
    <td class="text-center all-border">{{ $order->dispatch_end_date }}</td>
    <td class="text-center all-border">{{ $order->dispatch_recorded_quantity }}</td>
    <td class="text-center all-border">{{ $order->dispatch_rejected_quantity }}</td>
    <td class="text-center all-border">{{ $order->dispatch_net_quantity }}</td>
    <td class="text-center all-border">{{ $order->dispatch_remark }}</td>
</tr>
