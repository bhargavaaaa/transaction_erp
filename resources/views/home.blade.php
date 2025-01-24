@extends('layouts.app')

@section('title')
    Gantt
@endsection

@section('css')
    @include('layouts.includes.select2_styles')
    @include('layouts.includes.datatable_styles')
@endsection

@section('content')
<div class="content">
    <div class="row">
        <h2 class="content-heading common-title pt-0"><strong>GANTT</strong></h2>
    </div>

    <div class="block block-rounded block-mode-hidden">
        <div class="block-header block-header-default">
            <h3 class="block-title">Filters</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option"
                        data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content" id="collapse-filter-block">
            <div class="row">
                <div class="col-6 col-md-4 mb-2">
                    <label class="form-label" for="customer">Customer</label>
                    <select class="js-select2 form-select"
                            id="customer" name="customer" data-placeholder="Select Customer..">
                        <option></option>
                        @foreach ($customers as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <label class="form-label" for="part_name">Part</label>
                    <select class="js-select2 form-select"
                            id="part_name" name="part_name" data-placeholder="Select Part..">
                        <option></option>
                        @foreach ($parts as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <label class="form-label" for="metal">Metal</label>
                    <select class="js-select2 form-select"
                            id="metal" name="metal" data-placeholder="Select Metal..">
                        <option></option>
                        @foreach ($metals as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <button type="button" class="btn btn-alt-primary filter_btn">Filter</button>
                <button type="button" class="btn btn-alt-secondary ms-2 filter_clear_btn">Clear</button>
            </div>
        </div>
    </div>

    <!-- Dynamic Table with Export Buttons -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <div class="row w-100">
                <div class="col-auto">
                    <h3 class="block-title">
                        Orders
                    </h3>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
            {{-- Display DataTable --}}
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@include('modal_progress')
@endsection

@section('js')
    @include('layouts.includes.select2_scripts')
    @include('layouts.includes.datatable_scripts')
    {{ $dataTable->scripts() }}

    <script>
        $(function() {
            Codebase.helpersOnLoad(['jq-select2']);

            $(document).on("click", ".btn-block-option", function() {
                $('#collapse-filter-block').find('.select2-container').css('width', '100%');
            });

            $(document).on("click", ".filter_btn", function() {
                window.LaravelDataTables["order-table"].ajax.reload();
            });

            $(document).on("click", ".filter_clear_btn", function() {
                $("#customer").val("").trigger('change');
                $("#part_name").val("").trigger('change');
                $("#metal").val("").trigger('change');
                window.LaravelDataTables["order-table"].ajax.reload();
            });

            $('tbody').on('click', 'tr', function () {
                let data = window.LaravelDataTables["order-table"].row(this).data();
                $("#order_progress_modal").find("#embed_svg_code").html(data.svg_code);
                $("#order_progress_modal").modal("show");
            })
        });
    </script>
@endsection
