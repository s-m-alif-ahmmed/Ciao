@extends('auth.app')

@section('title', 'Verify Email')

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
                    <form class="login100-form validate-form" method="POST" action="{{ route('verification.send') }}" >
                        @csrf

                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </div>

                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Resend Verification Email
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Log Out') }}
                        </button>
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
