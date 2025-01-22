<hr/>
<form action="{{ route('other.destroy', $order) }}" method="POST" id="destroy_form" enctype="multipart/form-data">
    <div class="row">
        @csrf
        @method('DELETE')
        <h4>{{ $order->work_order_number }}</h4>
        <hr/>
        <input type="hidden" name="work_order_number" value="{{ $order->work_order_number }}">
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="other_end_date">Other End Date</label>
            <input type="text" class="js-flatpickr form-control @error('other_end_date') is-invalid @enderror" id="other_end_date" name="other_end_date" placeholder="Other End Date.."
                   value="{{ old('other_end_date', date('d-m-Y', strtotime($order->other_end_date))) }}"
                   data-date-format="d-m-Y" disabled>
            @error('other_end_date')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="other_recorded_quantity">Recorded Quantity</label>
            <input type="number" class="form-control" id="other_recorded_quantity" name="other_recorded_quantity" value="{{ $order->other_recorded_quantity }}"
                   placeholder="Recorded Quantity.." disabled>
            @error('other_recorded_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="other_rejected_quantity">Rejected Quantity</label>
            <input type="number" class="form-control" id="other_rejected_quantity" name="other_rejected_quantity" value="{{ $order->other_rejected_quantity }}"
                   placeholder="Rejected Quantity.." disabled>
            @error('other_rejected_quantity')
            <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>
        <div class="col-6 col-md-4 mb-2">
            <label class="form-label" for="other_remark">Other Remark</label>
            <input type="text" class="form-control" id="other_remark" name="other_remark" placeholder="Other Remark.." value="{{ $order->other_remark }}" disabled>
            @error('other_remark')
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
