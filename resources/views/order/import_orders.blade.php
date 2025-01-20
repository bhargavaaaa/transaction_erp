@extends('layouts.app')

@section('title')
    Import Orders
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Import Orders
                        </h3>
                    </div>
                    <div class="col-6 text-end">
                        <a href="javascript:history.back()" class="btn btn-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <form action="{{ route('import.orders.store') }}" method="POST" id="order_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="order_file">Import Orders File</label>
                                <input type="file" class="form-control @error('order_file') is-invalid @enderror" id="order_file" name="order_file">
                                @error('order_file')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label d-block" for="order_file">&nbsp;</label>
                                <a href="{{ url('templates/Import Order Template.xlsx') }}" class="btn btn-alt-primary" download>
                                    <i class="fa fa-download me-1"></i>
                                    Download Template
                                </a>
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
