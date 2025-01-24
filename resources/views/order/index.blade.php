@extends('layouts.app')

@section('title')
    Orders
@endsection

@section('css')
    @include('layouts.includes.datatable_styles')
    @include('layouts.includes.datatable_sticky_lastcolumn')
    @include('layouts.includes.sweetalert2_styles')
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-auto">
                        <h3 class="block-title">
                            Orders
                        </h3>
                    </div>
                    <div class="col text-end">
                        {!! getCreateButton(route('import.orders'), "Import Orders", "mb-1", "", "order-create") !!}
                        {!! getCreateButton(route('order.create'), "Add Order", "mb-1", "", "order-create") !!}
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                {{-- Display DataTable --}}
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@php $deleteName = "Order"; @endphp
@section('js')
    @include('layouts.includes.datatable_scripts')
    @include('layouts.includes.sweetalert2_scripts')
    {{ $dataTable->scripts() }}

    <script>
        $(function() {
            let deleteRoute = '{{ route('order.destroy', ':id') }}';

            $(document).on("click", ".deleteModel", function(e) {
                let deleteId = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this {{ $deleteName }}!",
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
                    if(resp.value) {
                        jQuery.ajax({
                            url: deleteRoute.replace(':id', deleteId),
                            method: 'DELETE',
                            success: function(result) {
                                if(result.status == true) {
                                    Swal.fire(
                                        "Deleted!",
                                        result.message,
                                        "success"
                                    );
                                    window.LaravelDataTables["order-table"].ajax.reload();
                                } else {
                                    Swal.fire(
                                        "Something went wrong!",
                                        result.message,
                                        "error"
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    "Something went wrong!",
                                    xhr.responseText,
                                    "error"
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
