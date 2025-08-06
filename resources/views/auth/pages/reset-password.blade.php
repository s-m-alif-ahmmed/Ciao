@extends('auth.app')

@section('title', 'Reset Password')

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
                    <form class="login100-form validate-form" method="POST" action="{{ route('password.store') }}" >
                        @csrf

                        <span class="login100-form-title">
                                Login
                            </span>

                        <div class="wrap-input100 validate-input" data-bs-validate = "Valid email is required: ex@abc.xyz">
                            <input class="input100" type="text" name="email" value="{{ old('email') }}" placeholder="Email">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                    <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                </span>
                        </div>
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

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

                        <div class="wrap-input100 validate-input" data-bs-validate = "Confirm Password is required">
                            <input class="input100" type="password" name="password_confirmation" placeholder="Confirm Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                    <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                </span>
                        </div>
                        @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="d-flex justify-content-between mt-4">
                            <div class="block">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div>
                            <div class="text-end">
                                <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Forgot Password?</a></p>
                            </div>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Reset Password
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
