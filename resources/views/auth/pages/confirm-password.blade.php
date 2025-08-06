@extends('auth.app')

@section('title', 'Confirm Password')

@push('styles')

@endpush

@section('content')

<!-- PAGE -->
<div class="page">
    <div>

        @php
            $systemSetting = App\Models\SystemSetting::first();
        @endphp

            <!-- CONTAINER OPEN -->
        <div class="col col-login mx-auto text-center">
            <a href="{{ route('login') }}" class="text-center">
                <img src="{{ asset($systemSetting->logo ?? 'frontend/eVento_logo.png') }}" class="header-brand-img" alt="">
            </a>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body">
                    <form class="login100-form validate-form" method="POST" action="{{ route('password.confirm') }}" >
                        @csrf

                        <div class="wrap-input100 validate-input" data-bs-validate = "Password is required">
                            <input class="input100" type="password" name="password" placeholder="Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                    <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                </span>
                        </div>
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
<!-- End PAGE -->

@endsection

@push('scripts')

@endpush
