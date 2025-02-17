@extends('layouts.app')

@section('title')
    Dispatch
@endsection

@section('css')
    @include('layouts.includes.datepicker_styles')
    @include('layouts.includes.sweetalert2_styles')
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-6">
                        <h3 class="block-title">
                            Dispatch
                        </h3>
                    </div>
                    <div class="col text-end">
                        {!! getCreateButton("javascript:;", "Import", "import mb-1", "data-bs-toggle='modal' data-bs-target='#import_model'", "dispatch-modify") !!}
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('dispatch.create') }}" method="POST" id="transaction_form"
                      enctype="multipart/form-data">
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
                <div class="row mt-4" id="result_div">

                </div>
            </div>
        </div>
    </div>
    @include('layouts.includes.import_modal')
@endsection

@section('js')
    @include('layouts.includes.datepicker_scripts')
    @include('layouts.includes.sweetalert2_scripts')
    <script>
        $(function() {
            let isLoading = false;

            Codebase.helpersOnLoad(['js-flatpickr', 'jq-datepicker']);

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
                    url: `{{ route('dispatch.create') }}`,
                    data: formData,
                    success: function (response) {
                        if (response.status === true) {
                            $('#result_div').html(response.html);
                            $(".js-flatpickr").flatpickr({
                                disableMobile: true
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
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
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on('submit', '#data_form', function (e) {
                e.preventDefault();
                if (!isLoading) {
                    isLoading = true;
                    $.ajax({
                        type: 'POST',
                        url: `{{ route('dispatch.store') }}`,
                        data: $("#data_form").serialize(),
                        headers: {
                            Accept: "application/json"
                        },
                        success: function (response) {
                            if (response.status == true) {
                                setTimeout(() => {
                                    $("#transaction_form").trigger('submit');
                                }, 300);
                            } else {
                                Codebase.helpers('jq-notify', {
                                    align: 'right',
                                    from: 'top',
                                    type: 'danger',
                                    icon: 'fa fa-times me-1',
                                    message: response.message
                                });
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
                        },
                        complete: function () {
                            isLoading = false;
                        }
                    });
                }
            });

            $(document).on('submit', '#destroy_form', function (e) {
                e.preventDefault();
                let url = $(this).attr('action');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this transaction!",
                    icon: "warning",
                    showCancelButton: !0,
                    customClass: {
                        confirmButton: "btn btn-danger m-1",
                        cancelButton: "btn btn-secondary m-1",
                    },
                    confirmButtonText: "Yes, delete it!",
                    html: !1,
                    preConfirm: (e) =>
                        new Promise((e) => {
                            setTimeout(() => {
                                e();
                            }, 50);
                        }),
                }).then((resp) => {
                    if (resp.value) {
                        if (!isLoading) {
                            isLoading = true;
                            $.ajax({
                                type: 'DELETE',
                                url: url,
                                headers: {
                                    Accept: "application/json"
                                },
                                success: function (response) {
                                    if (response.status === true) {
                                        Codebase.helpers('jq-notify', {
                                            align: 'right',
                                            from: 'top',
                                            type: 'success',
                                            icon: 'fa fa-times me-1',
                                            message: response.message
                                        });
                                        setTimeout(() => {
                                            $("#transaction_form").trigger('submit');
                                        }, 300);
                                    } else {
                                        Codebase.helpers('jq-notify', {
                                            align: 'right',
                                            from: 'top',
                                            type: 'danger',
                                            icon: 'fa fa-times me-1',
                                            message: response.message
                                        });
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
                                },
                                complete: function () {
                                    isLoading = false;
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('submit', '#import_form', function (e) {
                e.preventDefault();
                if (!isLoading) {
                    isLoading = true;

                    let formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: `{{ route('dispatch.import.store') }}`,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.status === true) {
                                $(".import_close").trigger('click');

                                Codebase.helpers('jq-notify', {
                                    z_index: 99999,
                                    align: 'right',
                                    from: 'top',
                                    type: 'success',
                                    icon: 'fa fa-check me-1',
                                    message: response.message
                                });

                                if($("#work_order_number").val()) {
                                    setTimeout(() => {
                                        $("#transaction_form").trigger('submit');
                                    }, 300);
                                }
                            } else {
                                Codebase.helpers('jq-notify', {
                                    z_index: 99999,
                                    align: 'right',
                                    from: 'top',
                                    type: 'danger',
                                    icon: 'fa fa-times me-1',
                                    message: response.message
                                });
                            }
                            isLoading = false;
                        },
                        error: function (xhr, status, error) {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: xhr.responseText
                            });
                            isLoading = false;
                        }
                    });
                }
            });
        });
    </script>
@endsection
