@extends('layouts.app')

@section('title')
    Add Order
@endsection

@section('css')
    @include('layouts.includes.datepicker_styles')
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Add Order
                        </h3>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('order.index') }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <form action="{{ route('order.store') }}" method="POST" id="order_form"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="work_order_number">Work Order Number</label>
                                <input type="text" class="form-control @error('work_order_number') is-invalid @enderror"
                                       id="work_order_number" name="work_order_number" placeholder="Work Order Number.." value="{{ old('work_order_number') }}">
                                @error('work_order_number')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="customer">Customer</label>
                                <input type="text" class="form-control @error('customer') is-invalid @enderror"
                                       id="customer" name="customer" placeholder="Customer.." value="{{ old('customer') }}" list="customers_list">
                                <datalist id="customers_list">
                                    @foreach($customers as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </datalist>
                                @error('customer')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="part_name">Part Name</label>
                                <input type="text" class="form-control @error('part_name') is-invalid @enderror"
                                       id="part_name" name="part_name" placeholder="Part Name.." value="{{ old('part_name') }}" list="parts_list">
                                <datalist id="parts_list">
                                    @foreach($parts as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </datalist>
                                @error('part_name')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="metal">Metal</label>
                                <input type="text" class="form-control @error('metal') is-invalid @enderror"
                                       id="metal" name="metal" placeholder="Metal.." value="{{ old('metal') }}" list="metals_list">
                                <datalist id="metals_list">
                                    @foreach($metals as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </datalist>
                                @error('metal')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="size">Size</label>
                                <input type="text" class="form-control @error('size') is-invalid @enderror"
                                       id="size" name="size" placeholder="Size.." value="{{ old('size') }}" list="sizes_list">
                                <datalist id="sizes_list">
                                    @foreach($sizes as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </datalist>
                                @error('size')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="quantity">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity" name="quantity" placeholder="Quantity.." value="{{ old('quantity', '0') }}" step="0.01">
                                @error('quantity')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="weight_per_pcs">Weight Per PCs</label>
                                <input type="number" class="form-control @error('weight_per_pcs') is-invalid @enderror"
                                       id="weight_per_pcs" name="weight_per_pcs" placeholder="Weight Per PCs.." value="{{ old('weight_per_pcs', '0.000') }}" step="0.001">
                                @error('weight_per_pcs')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="required_weight">Required Weight</label>
                                <input type="number" class="form-control @error('required_weight') is-invalid @enderror"
                                       id="required_weight" name="required_weight" placeholder="Required Weight.." value="{{ old('required_weight', '0.000') }}" step="0.001">
                                @error('required_weight')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="po_no">PO No.</label>
                                <input type="text" class="form-control @error('po_no') is-invalid @enderror"
                                       id="po_no" name="po_no" placeholder="PO No..." value="{{ old('po_no') }}">
                                @error('po_no')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="po_date">PO Date</label>
                                <input type="text"
                                       class="js-flatpickr form-control @error('po_date') is-invalid @enderror"
                                       value="{{ old('po_date', date('d-m-Y')) }}" id="po_date" name="po_date"
                                       placeholder="d-m-Y" data-date-format="d-m-Y">
                                @error('po_date')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="delivery_date">Delivery Date</label>
                                <input type="text"
                                       class="js-flatpickr form-control @error('delivery_date') is-invalid @enderror"
                                       value="{{ old('delivery_date') }}" id="delivery_date" name="delivery_date"
                                       placeholder="d-m-Y" data-date-format="d-m-Y">
                                @error('delivery_date')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="remark">Remark</label>
                                <input type="text" class="form-control @error('remark') is-invalid @enderror"
                                       id="remark" name="remark" placeholder="Remark.." value="{{ old('remark') }}">
                                @error('remark')
                                <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('layouts.includes.datepicker_scripts')
    @include('layouts.includes.validation_scripts')
    <script>
        $(function () {
            Codebase.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-validation']);

            jQuery("#order_form").validate({
                rules: {
                    "work_order_number": {
                        required: true,
                        remote: '{{ route('order.check-work-order-no-unique') }}'
                    },
                    "customer": {
                        required: true
                    },
                    "part_name": {
                        required: true
                    },
                    "metal": {
                        required: true
                    },
                    "size": {
                        required: true
                    },
                    "quantity": {
                        required: true,
                        number: true
                    },
                    "weight_per_pcs": {
                        required: true,
                        number: true
                    },
                    "required_weight": {
                        required: true,
                        number: true
                    },
                    "po_no": {
                        required: true
                    },
                    "po_date": {
                        required: true
                    },
                    "delivery_date": {
                        required: true
                    }
                },
                messages: {
                    work_order_number: {
                        remote: "Work order no has been already taken."
                    }
                }
            });

            $(document).on("input", "#quantity,#weight_per_pcs", function () {
                let quantity = parseFloat($("#quantity").val());
                quantity = !isNaN(quantity) ? quantity : 0;

                let weight_per_pcs = parseFloat($("#weight_per_pcs").val());
                weight_per_pcs = !isNaN(weight_per_pcs) ? weight_per_pcs : 0;

                $("#required_weight").val((quantity * weight_per_pcs).toFixed(3));
            });
        });
    </script>
@endsection
