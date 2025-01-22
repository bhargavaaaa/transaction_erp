<hr/>
<form action="{{ route('cutting.destroy', $order) }}" method="POST" id="destroy_form" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @method('DELETE')
        <h4>{{ $order->work_order_number }}</h4>
        <hr/>
        <input type="hidden" name="work_order_number" value="{{ $order->work_order_number }}">
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="cutting_end_date">Cutting End Date</label>
            <input type="text" class="js-flatpickr form-control @error('cutting_end_date') is-invalid @enderror" id="cutting_end_date" name="cutting_end_date" placeholder="Cutting End Date.."
                   value="{{ old('cutting_end_date', date('d-m-Y', strtotime($order->cutting_end_date))) }}"
                   data-date-format="d-m-Y" disabled>
            @error('cutting_end_date')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="cutting_recorded_quantity">Recorded Quantity</label>
            <input type="number" class="form-control" id="cutting_recorded_quantity" name="cutting_recorded_quantity" value="{{ $order->cutting_recorded_quantity }}"
                   placeholder="Recorded Quantity.." disabled>
            @error('cutting_recorded_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="cutting_rejected_quantity">Rejected Quantity</label>
            <input type="number" class="form-control" id="cutting_rejected_quantity" name="cutting_rejected_quantity" value="{{ $order->cutting_rejected_quantity }}"
                   placeholder="Rejected Quantity.." disabled>
            @error('cutting_rejected_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="cutting_remark">Cutting Remark</label>
            <input type="text" class="form-control" id="cutting_remark" name="cutting_remark" placeholder="Cutting Remark.." value="{{ $order->cutting_remark }}" disabled>
            @error('cutting_remark')
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
