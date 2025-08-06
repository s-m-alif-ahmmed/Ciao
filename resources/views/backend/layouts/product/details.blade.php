@extends('backend.app')

@section('title', 'Product Details')

@push('styles')

@endpush

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Product Details</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Table</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="form-control @error('name') is-invalid @enderror" disabled >
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
                                <label for="shop_id"> Shop Name</label>
                                <input type="text" name="shop_id" value="{{ $product->shop->name }}" class="form-control @error('name') is-invalid @enderror" disabled >
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
                                <label for="category_id"> Category Name</label>
                                <input type="text" name="category_id" value="{{ $product->category->name }}" class="form-control @error('name') is-invalid @enderror" disabled >
                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- SubCategory (will be loaded on AJAX) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subcategory_id"> Subcategory Name</label>
                                <input type="text" name="subcategory_id" value="{{ $product->subCategory->name }}" class="form-control @error('name') is-invalid @enderror" disabled >
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
                                <input type="number" name="price" value="{{ $product->price }}" class="form-control @error('price') is-invalid @enderror" step="0.01" disabled >
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Stock</label>--}}
{{--                                <input type="number" name="stock" value="{{ $product->stock }}" class="form-control @error('stock') is-invalid @enderror" disabled >--}}
{{--                                @error('stock')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity (e.g., 500 gm, 1 Ltr)</label>
                                <input type="text" name="quantity" value="{{ $product->quantity }}" class="form-control @error('quantity') is-invalid @enderror" disabled >
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
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" disabled >{{ $product->description }}</textarea>
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
                                <input type="file" name="thumbnail" data-default-file="{{ asset( $product->thumbnail ?? '') }}" value="{{ $product->name }}" class="form-control dropify @error('description') is-invalid @enderror" />
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
                                <div class="">
                                    @foreach($product->images as $image)
                                        <img src="{{ asset( $image->image_path ) }}" alt="{{ $product->name }}" style="height: 50px; width: auto;" class="m-2" />
                                    @endforeach
                                </div>
                                @error('images')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <a href="{{ route('product.index', ['id' => $product->category_id]) }}" class="btn btn-danger">back</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
