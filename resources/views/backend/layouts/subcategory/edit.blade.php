@extends('backend.app')

@section('title', 'Edit Subcategory')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Subcategory</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Table</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subcategory</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('subcategory.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updating -->

                        <div class="row">

                            <!-- Category Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Select Category</label>
                                    <select id="category_id" name="category_id" class="form-control" required>
                                        <option value="">Choose Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- SubCategory -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Subcategory</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Enter Subcategory" value="{{ old('name', $data->name) }}" required>
                                </div>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- SubCategory -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" data-default-file="{{ asset( $data->image ?? '') }}" value="{{ $data->image }}" id="image" class="dropify form-control">
                                </div>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Subcategory</button>
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
