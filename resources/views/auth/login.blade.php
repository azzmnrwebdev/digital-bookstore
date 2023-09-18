@extends('layouts.app')

@section('title', config('app.name') . ' - Sign In')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}" />

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" />

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
@endprepend

@section('content')
    <div class="container">
        <div class="row justify-content-center justify-content-lg-end">
            <div class="col-xs col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="card shadow" style="border-radius: 20px;">
                    <div class="card-body p-0" style="padding: 40px 30px !important">

                        <div class="heading text-center">
                            <h5 class="card-title">{{ __('Sign In') }}</h5>
                            <p class="card-text">{{ __('Please login to your account') }}</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email_or_username" class="form-label">{{ __('Email or Username') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="email_or_username" id="email_or_username"
                                    class="form-control {{ old('email_or_username') && !$errors->has('email_or_username') ? 'cek-valid' : '' }}
                                    @error('email_or_username') is-invalid @enderror"
                                    placeholder="Enter your email or username" value="{{ old('email_or_username') }}"
                                    autocomplete="username">

                                @error('email_or_username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }} <span
                                        class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control {{ old('password') ? 'cek-valid' : '' }} @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" value="{{ old('password') }}"
                                        autocomplete="current-password" aria-describedby="toggle-password">
                                    <span class="input-group-text" id="toggle-password"><i
                                            class="bi bi-eye-slash-fill"></i></span>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mb-3">
                                {{ __('Sign In') }}
                            </button>

                            @if (Route::has('password.request'))
                                <div class="col-12 mb-1">
                                    <p class="card-text text-start"
                                        style="font-family: 'Poppins'; font-weight: 400; font-size: 13px;">
                                        <a href="{{ route('password.request') }}"
                                            style="text-decoration: none; color: #42B549;">{{ __('Forgot password?') }}</a>
                                    </p>
                                </div>
                            @endif

                            <div class="col-12">
                                <p class="card-text text-start"
                                    style="font-family: 'Poppins'; font-weight: 400; font-size: 13px;">
                                    {{ __("Don't have an account yet?") }} <a href="{{ route('register') }}"
                                        style="text-decoration: none; color: #42B549; font-weight: 500">{{ __('Register here') }}</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Informasi Pesan Status Is Active -->
    <div class="modal fade" id="modalInformasiStatusIsActive" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="modalInformasiStatusIsActiveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalInformasiStatusIsActiveLabel">{{ __('Information') }}</h1>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- jQuery CDN --}}
    <script src="{{ url('https://code.jquery.com/jquery-3.6.3.min.js') }}"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

    {{-- custom js --}}
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        togglePassword.addEventListener('click', () => {
            const eyeIcon = togglePassword.querySelector('i');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            eyeIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill') || eyeIcon.classList.replace(
                'bi-eye-slash-fill', 'bi-eye-fill');
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "8000",
            "extendedTimeOut": "8000",
        }

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Registration successful");
        @endif

        @if ($errors->any())
            const errors = @json($errors->all());
            errors
                .slice()
                .reverse()
                .forEach((error) => {
                    toastr.error(error, 'Error Input');
                });
        @endif

        @if (Session::has('errorIsActive'))
            toastr.error("{!! Session::get('errorIsActive') !!}", "Active Account");

            document.querySelector('#modalInformasiStatusIsActive .modal-body').innerHTML =`{!! Session::get('errorIsActive') !!}`;
            const modal = new bootstrap.Modal("#modalInformasiStatusIsActive");
            modal.show();
        @endif
    </script>
@endprepend
