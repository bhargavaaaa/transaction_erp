@extends('layouts.app')

@section('title')
    Order Process Card
@endsection

@section('css')
    <style>
        @page {
            margin: 20px 0 0 0;
        }

        @media print {
            #search_div {
                display: none;
            }
        }

        .all-border {
            border: 1px solid black;
        }

        .font-weight-bold {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <div class="block block-rounded" id="search_div">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Search Order
                        </h3>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('order-process-card.index') }}" id="transaction_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col col-md-4 mb-2">
                            <label class="form-label" for="work_order_number">Work Order Number</label>
                            <input type="text" class="form-control" id="work_order_number" name="work_order_number" placeholder="Work Order Number..">
                        </div>
                        <div class="col-auto mb-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary" id="transaction_form_submit_btn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded" id="result_div" style="display: none">
            <div class="block-content block-content-full overflow-auto">
                <div class="row mb-1">
                    <div class="col-12 text-center">
                        <h5 class="mb-0">Order Process Card</h5>
                    </div>
                </div>
                <table class="table" id="render_table">
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            let isLoading = false;

            $(document).on('submit', '#transaction_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formDataString = $(this).serialize();
                let formData = {};
                formDataString.split('&').forEach(function (pair) {
                    pair = pair.split('=');
                    formData[pair[0]] = decodeURIComponent(pair[1] || '');
                });

                $.ajax({
                    type: 'GET',
                    url: `{{ route('order-process-card.index') }}`,
                    data: formData,
                    success: function (response) {
                        if (response.status === true) {
                            $('#result_div').show();
                            $("#render_table").html(response.html);
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                            $('#result_div').hide();
                            $("#render_table").html("");
                        }
                    },
                    error: function (xhr) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                        $('#result_div').hide();
                        $("#render_table").html("");
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });
        });
    </script>
@endsection
