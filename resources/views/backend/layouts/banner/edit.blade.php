@extends('backend.app')

@section('title', 'Edit Banner')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Banner</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Table</a></li>
                <li class="breadcrumb-item active" aria-current="page">Banner</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="shop_id">Shop</label>
                                <div class="">
                                    <select class="form-control select2-show-search form-select" name="shop_id" id="shop_id" required >
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}" {{ $banner->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('shop_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="banner_image">Banner Image</label>
                                <input type="file" name="banner_image" class="form-control dropify"
                                       data-default-file="{{ asset($banner->banner_image) }}">
                                @error('banner_image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Banner</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@endpush
