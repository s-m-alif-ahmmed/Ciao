@extends('auth.app')

@section('title', 'Confirm Password')

@push('styles')

@endpush

@section('content')

    <!-- PAGE -->
    <div class="page">
        <div>

            <div class="container-login100">
                <div class="wrap-login100 p-0">
                    <div class="card-body">

                        <form class="login100-form validate-form" method="POST" action="{{ route('user.delete.confirm') }}" >
                            @csrf

                            <div class="text-center">
                                <h2>Delete Your Account</h2>
                                <span>
                                    Are you sure you want to delete your account?
                                    This action cannot be undone.
                                </span>
                            </div>

                            <div class="container-login100-form-btn">
                                <button type="submit" class="login100-form-btn btn-danger">
                                    Yes, Delete My Account
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
