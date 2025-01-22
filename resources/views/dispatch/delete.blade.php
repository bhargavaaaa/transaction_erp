<hr/>
<form action="{{ route('dispatch.destroy', $order) }}" method="POST" id="destroy_form" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @method('DELETE')
        <h4>{{ $order->work_order_number }}</h4>
        <hr/>
        <input type="hidden" name="work_order_number" value="{{ $order->work_order_number }}">
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="dispatch_end_date">Dispatch End Date</label>
            <input type="text" class="js-flatpickr form-control @error('dispatch_end_date') is-invalid @enderror" id="dispatch_end_date" name="dispatch_end_date" placeholder="Dispatch End Date.."
                   value="{{ old('dispatch_end_date', date('d-m-Y', strtotime($order->dispatch_end_date))) }}"
                   data-date-format="d-m-Y" disabled>
            @error('dispatch_end_date')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="dispatch_recorded_quantity">Recorded Quantity</label>
            <input type="number" class="form-control" id="dispatch_recorded_quantity" name="dispatch_recorded_quantity" value="{{ $order->dispatch_recorded_quantity }}"
                   placeholder="Recorded Quantity.." disabled>
            @error('dispatch_recorded_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="dispatch_rejected_quantity">Rejected Quantity</label>
            <input type="number" class="form-control" id="dispatch_rejected_quantity" name="dispatch_rejected_quantity" value="{{ $order->dispatch_rejected_quantity }}"
                   placeholder="Rejected Quantity.." disabled>
            @error('dispatch_rejected_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="dispatch_remark">Dispatch Remark</label>
            <input type="text" class="form-control" id="dispatch_remark" name="dispatch_remark" placeholder="Dispatch Remark.." value="{{ $order->dispatch_remark }}" disabled>
            @error('dispatch_remark')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="auto">
            <label class="form-label d-block">&nbsp;</label>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>
