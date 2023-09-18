@extends('penulis.index')
@section('title-head', 'Change Password')
@section('title-content', 'Change Password')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        .form-control:focus {
            border-color: #42B549;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Account Setting') }}</li>
    <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body">
            @if (Session('success'))
                <div id="alert-msg" class="alert alert-success alert-dismissible mb-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ Session('success') }}
                </div>
            @endif

            <form action="{{ route('penulis.update_password') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="current_password">{{ __('Current Password') }} <span
                            class="text-danger">{{ __('*') }}</span></label>
                    <input type="password" name="current_password" id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror">

                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">{{ __('New Password') }} <span
                            class="text-danger">{{ __('*') }}</span></label>
                    <input type="password" name="new_password" id="new_password"
                        class="form-control @error('new_password') is-invalid @enderror">

                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="form-control">
                </div>

                <button type="submit" class="btn btn-sm btn-warning"><i
                        class="fas fa-pencil-alt mr-3"></i>{{ __('Change Password') }}</button>
            </form>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "8000",
            "extendedTimeOut": "8000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
        }

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
    </script>
@endprepend
