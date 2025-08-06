@extends('backend.app')

@section('title', 'Edit Product')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product</h1>
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
                    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $product->name }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Shop -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shop_id">Select Shop</label>
                                    <select id="shop_id" name="shop_id" class="form-control @error('shop_id') is-invalid @enderror">
                                        <option value="">Choose Shop</option>
                                        @foreach ($shops as $shop)
                                            <option value="{{ $shop->id }}" {{ $shop->id == $product->shop_id ? 'selected' : '' }}>
                                                {{ $shop->name }} ({{$shop->location}})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shop_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Select Category</label>
                                    <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">Choose Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- SubCategory -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id">Select Subcategory</label>
                                    <select id="subcategory_id" name="subcategory_id" class="form-control @error('subcategory_id') is-invalid @enderror">
                                        <option value="">Choose Subcategory</option>
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ $subcategory->id == $product->sub_category_id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subcategory_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ $product->price }}" step="0.01" required>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Stock</label>--}}
{{--                                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ $product->stock }}" required>--}}
{{--                                    @error('stock')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                            <strong>{{ $message }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quantity (e.g., 500 gm, 1 Ltr, kg)</label>
                                    <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ $product->quantity }}">
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ $product->description }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Thumbnail</label>
                                    <input type="file" name="thumbnail" value="{{ $product->thumbnail ?? null }}" data-default-file="{{ asset( $product->thumbnail ?? null) }}"  class="form-control dropify @error('description') is-invalid @enderror" />
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Images (Max images: 5)</label>
                                    <input type="file" name="images[]" id="imageUpload" class="form-control @error('images') is-invalid @enderror" multiple accept="image/*">
                                    @error('images')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="">
                                    @foreach($product->images as $images)
                                        <img src="{{ asset($images->image_path) }}" alt="" style="height: 100px; width: auto; margin: 5px;">
                                        <input type="checkbox" name="remove_images[]" value="{{ $images->id }}" class="image-checkbox" multiple />
                                    @endforeach
                                </div>
                                <div id="imagePreviewContainer" class="d-flex flex-wrap mt-2"></div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        let selectedFiles = [];

        document.getElementById('imageUpload').addEventListener('change', function(event) {
            let newFiles = Array.from(event.target.files);
            let previewContainer = document.getElementById('imagePreviewContainer');
            let dataTransfer = new DataTransfer();

            if (selectedFiles.length + newFiles.length > 5) {
                alert('You can upload a maximum of 5 images.');
                return;
            }

            newFiles.forEach(file => selectedFiles.push(file));
            updatePreview(previewContainer);
            updateFileInput();
        });

        function updatePreview(previewContainer) {
            previewContainer.innerHTML = "";
            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let imageDiv = document.createElement('div');
                    imageDiv.classList.add('position-relative', 'm-2');

                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '100px';
                    img.style.height = '100px';

                    let removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0');
                    removeBtn.onclick = function() {
                        selectedFiles.splice(index, 1);
                        updatePreview(previewContainer);
                        updateFileInput();
                    };

                    imageDiv.appendChild(img);
                    imageDiv.appendChild(removeBtn);
                    previewContainer.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            let dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            document.getElementById('imageUpload').files = dataTransfer.files;
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#category_id').on('change', function() {
                var categoryId = $('#category_id').val();

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('get.subcategories') }}",
                        type: "GET",
                        data: {
                            category_id: categoryId,
                        },
                        success: function(data) {
                            $('#subcategory_id').html(
                                '<option value="">Choose SubCategory</option>');
                            $.each(data, function(key, value) {
                                $('#subcategory_id').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subcategory_id').html('<option value="">Choose SubCategory</option>');
                }
            });
        });
    </script>
@endpush
