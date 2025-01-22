<hr/>
<form action="{{ route('turning.destroy', $order) }}" method="POST" id="destroy_form" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @method('DELETE')
        <h4>{{ $order->work_order_number }}</h4>
        <hr/>
        <input type="hidden" name="work_order_number" value="{{ $order->work_order_number }}">
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="turning_end_date">Turning End Date</label>
            <input type="text" class="js-flatpickr form-control @error('turning_end_date') is-invalid @enderror" id="turning_end_date" name="turning_end_date" placeholder="Turning End Date.."
                   value="{{ old('turning_end_date', date('d-m-Y', strtotime($order->turning_end_date))) }}"
                   data-date-format="d-m-Y" disabled>
            @error('turning_end_date')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="turning_recorded_quantity">Recorded Quantity</label>
            <input type="number" class="form-control" id="turning_recorded_quantity" name="turning_recorded_quantity" value="{{ $order->turning_recorded_quantity }}"
                   placeholder="Recorded Quantity.." disabled>
            @error('turning_recorded_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="turning_rejected_quantity">Rejected Quantity</label>
            <input type="number" class="form-control" id="turning_rejected_quantity" name="turning_rejected_quantity" value="{{ $order->turning_rejected_quantity }}"
                   placeholder="Rejected Quantity.." disabled>
            @error('turning_rejected_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="turning_remark">Turning Remark</label>
            <input type="text" class="form-control" id="turning_remark" name="turning_remark" placeholder="Turning Remark.." value="{{ $order->turning_remark }}" disabled>
            @error('turning_remark')
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
