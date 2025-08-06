@extends('backend.app')

@section('title', 'Edit Tax')

@push('style')

@endpush

@section('content')

    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Form</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tax</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}


    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <form action="{{ route('taxes.update', $data->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tax</label>
                            <input type="text" name="tax" id="name" class="form-control" value="{{ old('tax', $data->tax) }}"
                                placeholder="Enter tax name" required>
                            @error('tax')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
