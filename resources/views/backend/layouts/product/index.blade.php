@extends('backend.app')

@section('title', 'Products List')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Products List</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Table</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('product.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Create Product
                        </a>
                        <button type="button" id="duplicate-btn" class="btn btn-warning mx-2">Duplicate</button>
                        <button type="button" id="bulk-delete-btn" class="btn btn-danger mx-2">
                            <i class="fe fe-trash"></i> Delete Selected
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">
                                        <input type="checkbox" id="select-all">Select All
                                    </th>
                                    <th class="wd-15p border-bottom-0">Shop</th>
                                    <th class="wd-15p border-bottom-0">Product</th>
                                    <th class="wd-15p border-bottom-0">Image</th>
                                    <th class="wd-15p border-bottom-0">Price</th>
                                    <th class="wd-15p border-bottom-0">Category</th>
                                    <th class="wd-15p border-bottom-0">Status</th>
                                    <th class="wd-15p border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- dynamic data will be rendered here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="shopModel" tabindex="-1" aria-labelledby="shopModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('products.duplicate-shop') }}" method="POST" id="shop-duplicate">
                    @csrf
                    @method('POST')

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="shopModelLabel">Duplicate Product for Shops</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="product-id" />

                        <div class="form-group">
                            <label class="form-label">Select Shops</label>
                            <select name="shop_id[]" multiple class="form-control select2-show-search form-select" data-placeholder="Choose one">
                                <option label="Choose one"></option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" >{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Duplicate Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            // Handle Select All Checkbox
            $('#select-all').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('.product-select').prop('checked', isChecked);
            });

            // Handle individual checkbox selection
            $(document).on('change', '.product-select', function () {
                if (!$(this).is(':checked')) {
                    $('#select-all').prop('checked', false); // Uncheck "Select All" if one is unchecked
                } else if ($('.product-select:checked').length === $('.product-select').length) {
                    $('#select-all').prop('checked', true); // Check "Select All" if all are checked
                }
            });

            // Bulk Delete Button Click Handler
            $('#bulk-delete-btn').on('click', function () {
                var selectedProducts = [];
                $('.product-select:checked').each(function () {
                    selectedProducts.push($(this).val());
                });

                if (selectedProducts.length === 0) {
                    alert('Please select at least one product to delete.');
                    return;
                }

                // Confirm Deletion
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Selected products will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("products.bulk-delete") }}',
                            method: 'Delete',
                            data: {
                                product_ids: selectedProducts,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                toastr.success(response.message);
                                $('#datatable').DataTable().ajax.reload();
                            },
                            error: function () {
                                toastr.error('An error occurred while deleting the products.');
                            }
                        });
                    }
                });
            });

        });


        $(document).on('change', '.shop-select', function() {
            let productId = $(this).data('product-id');
            let shopId = $(this).val();

            $.ajax({
                url: "{{ route('product.updateShop') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    shop_id: shopId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Duplicate Button Click Handler
            $('#duplicate-btn').on('click', function () {
                // Get selected product IDs
                var selectedProducts = [];
                $('.product-select:checked').each(function () {
                    selectedProducts.push($(this).val());
                });

                if (selectedProducts.length === 0) {
                    alert('Please select at least one product to duplicate.');
                    return;
                }

                // Send an AJAX request to duplicate the selected products
                $.ajax({
                    url: '{{ route("products.duplicate") }}',
                    method: 'POST',
                    data: {
                        product_ids: selectedProducts,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message); // Show success message
                        $('#datatable').DataTable().ajax.reload(); // Reload the datatable
                    },
                    error: function () {
                        toastr.success('An error occurred while duplicating the products.');
                    }
                });
            });
        });

        $(document).on('click', '.details-icn', function() {
            var productId = $(this).data('id'); // Get the product ID from the button's data-id attribute
            $('#product-id').val(productId); // Set the product ID in the hidden input field
        });

        $(document).ready(function () {
            // Duplicate Button Click Handler
            $('#shop-duplicate').on('submit', function (e) {
                e.preventDefault();  // Prevent default form submission

                // Get selected shop IDs
                var selectedShops = $('select[name="shop_id[]"]').val();

                if (selectedShops.length === 0) {
                    alert('Please select at least one shop to duplicate.');
                    return;
                }

                // Get the selected product ID from the hidden input field
                var productId = $('#product-id').val();

                // Send an AJAX request to duplicate the selected product for the selected shops
                $.ajax({
                    url: '{{ route("products.duplicate-shop") }}',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        shop_id: selectedShops,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message); // Show success message
                        $('#datatable').DataTable().ajax.reload(); // Reload the datatable
                        $('#shopModel').modal('hide'); // Close the modal
                    },
                    error: function () {
                        toastr.error('An error occurred while duplicating the product.');
                    }
                });
            });
        });

        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('product.index', ['id' => $category->id]) }}",
                        type: "GET",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'select',
                            name: 'select',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'shop',
                            name: 'shop',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'product_name',
                            name: 'product_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'price',
                            name: 'price',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'subcategory',
                            name: 'subcategory',
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
                        }
                    ],
                    dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                });
            }
        });


        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
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
                    statusChange(id);
                }
            });
        }
        // Status Change
        function statusChange(id) {
            let url = '{{ route('product.status', ':id') }}';
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function(resp) {
                    // Reloade DataTable
                    $('#datatable').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    // location.reload();
                }
            });
        }

        // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Button
        function deleteItem(id) {
            let url = '{{ route('product.destroy', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable').DataTable().ajax.reload();
                    if (resp['t-success']) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    </script>
@endpush
