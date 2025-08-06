@extends('backend.app')

@section('title', 'List of Orders')

@push('style')
@endpush

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">List of Orders</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Villa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order No.</th>
                                    <th>Name</th>
                                    <th>Valet Email</th>
                                    <th>Shop Name</th>
                                    <th>Pay Method</th>
                                    <th>Payment ID</th>
                                    <th>Tax</th>
                                    <th>Valet Charge</th>
                                    <th>Valet Extra Charge</th>
                                    <th>Platform Fee</th>
                                    <th>Total Price</th>
                                    <th>Sub Total price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- dynamic data --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });
            if (!$.fn.DataTable.isDataTable('#datatable')) {
                let dTable = $('#datatable').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    processing: true,
                    responsive: true,
                    serverSide: true,

                    language: {
                        processing: `<div class="text-center">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>`
                    },

                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                    ajax: {
                        url: "{{ route('orders.pending.index') }}",
                        type: "GET",
                    },

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'order_number',
                            name: 'order_number',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'valet_email',
                            name: 'valet_email',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'shop_name',
                            name: 'shop_name',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'payment_method',
                            name: 'payment_method',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'payment_id',
                            name: 'payment_id',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'tax',
                            name: 'tax',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'valet_charge',
                            name: 'valet_charge',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'valet_charge_extra',
                            name: 'valet_charge_extra',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'platform_fee',
                            name: 'platform_fee',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'sub_total',
                            name: 'sub_total',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'total_price',
                            name: 'total_price',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                dTable.buttons().container().appendTo('#file_exports');
                new DataTable('#example', {
                    responsive: true
                });
            }
        });
        // Status Change Confirm Alert
        function showStatusChangeAlert(id, newStatus) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id, newStatus);
                }
            });
        }

        // Status Change Function
        function statusChange(id, newStatus) {
            // console.log("Order ID:", id);
            // console.log("New Status:", newStatus);

            let url = '{{ route('orders.status', ':id') }}';
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(resp) {
                    console.log(resp);
                    $('#datatable').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred while updating the status.');
                }
            });
        }
    </script>
@endpush
