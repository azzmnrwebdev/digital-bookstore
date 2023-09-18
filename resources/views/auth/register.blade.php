@extends('layouts.app')

@section('title', config('app.name') . ' - Sign Up')

@prepend('styles')
    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css') }}" />

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" />

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}" />
@endprepend

@section('content')
    <div class="container">
        <div class="row justify-content-center justify-content-lg-end">
            <div class="col-xs col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="card shadow" style="border-radius: 20px;">
                    <div class="card-body p-0" style="padding: 40px !important">
                        <div class="heading text-center">
                            <h5 class="card-title">{{ __('Sign Up') }}</h5>
                            <p class="card-text">{{ __('Please register to create a new account') }}</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="step step-active">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">{{ __('Fullname') }} <span
                                            class="text-danger">{{ __('*') }}</span></label>
                                    <input type="text" name="fullname" id="fullname"
                                        class="form-control {{ old('fullname') ? 'cek-valid' : '' }}
                                        @error('fullname') is-invalid @enderror"
                                        placeholder="Fullname" value="{{ old('fullname') }}" autocomplete="name">

                                    @error('fullname')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Username') }} <span
                                            class="text-danger">{{ __('*') }}</span></label>
                                    <input type="text" name="username" id="username"
                                        class="form-control {{ old('username') ? 'cek-valid' : '' }}
                                        @error('username') is-invalid @enderror"
                                        placeholder="Username" value="{{ old('username') }}" autocomplete="username"">

                                    @error('username')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }} <span
                                            class="text-danger">{{ __('*') }}</span></label>
                                    <input type="email" name="email" id="email"
                                        class="form-control {{ old('email') ? 'cek-valid' : '' }}
                                        @error('email') is-invalid @enderror"
                                        placeholder="Email" value="{{ old('email') }}" autocomplete="email">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <center><small class="text-muted">{{ __('Step 1/2 - Personal Information') }}</small>
                                </center>

                                <button type="button" class="btn btn-success w-100 my-3 next-step">
                                    {{ __('Next') }}
                                </button>
                            </div>

                            <div class="step">
                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('User Role') }} <span
                                            class="text-danger">*</span></label>
                                    <select id="role" name="role"
                                        class="form-select {{ old('role') ? 'cek-valid' : '' }} @error('role') is-invalid @enderror">
                                        <option value="" disabled selected>-- Select Role --</option>
                                        <option value="penulis" {{ old('role') === 'penulis' ? 'selected' : '' }}>
                                            {{ __('Writer') }}</option>
                                        <option value="pembaca" {{ old('role') === 'pembaca' ? 'selected' : '' }}>
                                            {{ __('Reader') }}</option>
                                    </select>

                                    @error('role')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input id="password" type="password"
                                            class="form-control {{ old('password') ? 'cek-valid' : '' }}
                                            @error('password') is-invalid @enderror"
                                            name="password" placeholder="Password" value="{{ old('password') }}"
                                            autocomplete="off" aria-describedby="toggle-password">
                                        <span class="input-group-text" id="toggle-password"><i
                                                class="bi bi-eye-slash-fill"></i></span>

                                        @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Confirm password"
                                        value="{{ old('password_confirmation') }}" autocomplete="off">
                                </div>

                                <div id="file-cv" class="d-none mb-3">
                                    <label for="cv" class="form-label">{{ __('CV or Portfolio') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="cv" name="cv"
                                        class="form-control @error('cv') is-invalid @enderror" accept=".pdf">

                                    @error('cv')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <center><small class="text-muted">{{ __('Step 2/2 - Account Data') }}</small></center>

                                <div class="d-flex justify-content-between my-3">
                                    <button type="button" class="btn btn-secondary prev-step" style="width: 45%;">
                                        </i> {{ __('Previous') }}
                                    </button>

                                    <button type="submit" class="btn btn-success" style="width: 45%;">
                                        {{ __('Sign Up') }}
                                    </button>
                                </div>
                            </div>

                            <p class="card-text" style="font-family: 'Poppins'; font-weight: 400; font-size: 13px;">
                                {{ __('Already have an account?') }} <a href="{{ route('login') }}"
                                    style="text-decoration: none; color: #42B549; font-weight: 500">{{ __('Login here') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- jQuery cdn --}}
    <script src="{{ url('https://code.jquery.com/jquery-3.6.3.min.js') }}"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

    {{-- custom js --}}
    <script src="{{ asset('assets/js/register.js') }}"></script>

    <script>
        @if (Session::has('error'))
            const errors = {!! json_encode(Session::get('error')) !!};
            showToast('error', errors, 'Error Input');
        @endif
    </script>
@endprepend
