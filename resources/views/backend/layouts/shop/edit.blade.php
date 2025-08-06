@extends('backend.app')

@section('title', 'Edit Shop')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Shop</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Table</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- PUT method for updating -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $shop->name) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control" value="{{ old('name', $shop->latitude) }}" placeholder="Enter latitude (e.g., 23.8103)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control" value="{{ old('name', $shop->longitude) }}" placeholder="Enter longitude (e.g., 23.8103)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Map Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $shop->location) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Number (Unique)</label>
                                    <input type="text" name="stall_number" class="form-control" value="{{ old('stall_number', $shop->stall_number) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Images</label>
                                    <input type="file" name="images[]" class="form-control" multiple>
                                    <div class="mt-2">
                                        @foreach ($shop->images as $image)
                                            <img src="{{ asset($image->image) }}" class="img-fluid img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Shop</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush

