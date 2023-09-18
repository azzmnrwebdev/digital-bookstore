@extends('admin.index')
@section('title-head', 'Create User')
@section('title-content', 'Create User')

@prepend('styles')
    <style>
        .form-control:focus {
            border-color: #42B549;
        }

        .background-image {
            z-index: -1;
            width: 100%;
            height: 150px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .profile-image {
            left: 30px;
            z-index: 1;
            width: 100px;
            height: 100px;
            bottom: -50px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .mb-custom {
            margin-bottom: 50px;
        }

        @media (min-width: 576px) {
            .background-image {
                height: 180px;
            }
        }

        @media (min-width: 768px) {
            .background-image {
                height: 230px;
            }

            .profile-image {
                width: 130px;
                height: 130px;
                bottom: -65px;
            }

            .mb-custom {
                margin-bottom: 65px;
            }
        }

        @media (min-width: 1200px) {
            .background-image {
                height: 300px;
            }

            .profile-image {
                width: 150px;
                height: 150px;
                bottom: -75px;
            }

            .mb-custom {
                margin-bottom: 75px;
            }
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('manageuser.index') }}">{{ __('Manage User') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    {{-- image preview --}}
                    <div class="card mb-4 shadow-none">
                        <div class="card-header bg-dark">
                            <h3 class="card-title">{{ __('Image Preview') }}</h3>
                        </div>

                        <div class="card-body px-0 pb-0 pt-2">
                            <div class="position-relative mb-custom">
                                <img src="{{ asset('assets/image/no-image.png') }}" id="backgroundView"
                                    class="background-image rounded" alt="Background Image">

                                <img src="{{ asset('assets/image/empty.jpg') }}" id="avatarView"
                                    class="profile-image position-absolute" style="" alt="Foto Profile">
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('manageuser.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- fullname --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="fullname" class="form-label">{{ __('Fullname') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" name="fullname" id="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    value="{{ old('fullname') }}" placeholder="Fullname">

                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- username --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="username" class="form-label">{{ __('Username') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" name="username" id="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" placeholder="Username">

                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- email --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="email" class="form-label">{{ __('Email') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="Email">

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- no handphone --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="phone_number" class="form-label">{{ __('Phone Number') }}</label>
                                <input type="tel" name="phone_number" id="phoneNumber"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="Cth: 08123456789123">

                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- gender --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="gender" class="form-label">{{ __('Gender') }}</label>

                                <select name="gender" id="gender"
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">{{ __('-- Select Gender --') }}</option>

                                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>
                                        {{ __('Male') }}
                                    </option>
                                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>
                                        {{ __('Female') }}
                                    </option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>
                                        {{ __('Other') }}
                                    </option>
                                </select>

                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- tanggal lahir --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="tanggalLahir" class="form-label">{{ __('Date of Birth') }}</label>
                                <input type="date" name="tgl_lahir" id="tanggalLahir"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror"
                                    value="{{ old('tgl_lahir') }}" max="2006-12-31">

                                @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- role --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="role" class="form-label">{{ __('Role') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>

                                <select name="role" id="role"
                                    class="form-control @error('role') is-invalid @enderror">
                                    <option value="" disabled selected>{{ __('-- Select Role --') }}</option>

                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                        {{ __('Admin') }}
                                    </option>
                                    <option value="penulis" {{ old('role') === 'penulis' ? 'selected' : '' }}>
                                        {{ __('Writer') }}
                                    </option>
                                    <option value="pembaca" {{ old('role') === 'pembaca' ? 'selected' : '' }}>
                                        {{ __('Reader') }}
                                    </option>
                                </select>

                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- password --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="password" class="form-label">{{ __('Password') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    value="{{ old('password') }}" placeholder="Password">

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- bio --}}
                            <div class="form-group col-12 d-none" id="inputBio">
                                <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="5">{{ old('bio') }}</textarea>

                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- foto profile --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="avatar" id="avatar"
                                        class="custom-file-input @error('avatar') is-invalid @enderror"
                                        accept=".png, .jpg, .jpeg">
                                    <label class="custom-file-label" for="avatar">{{ __('Choose avatar') }}</label>

                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- background --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="background" class="form-label">{{ __('Background') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="background" id="background"
                                        class="custom-file-input @error('background') is-invalid @enderror"
                                        accept=".png, .jpg, .jpeg">
                                    <label class="custom-file-label"
                                        for="background">{{ __('Choose background') }}</label>

                                    @error('background')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- cv --}}
                            <div class="form-group col-12 d-none" id="fileCV">
                                <label for="cv" class="form-label">{{ __('Curriculum Vitae (CV)') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <div class="custom-file">
                                    <input type="file" name="cv" id="cv"
                                        class="custom-file-input @error('cv') is-invalid @enderror" accept=".pdf">
                                    <label class="custom-file-label" for="cv">{{ __('Choose file') }}</label>

                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-success"><i
                                class="fas fa-check mr-3"></i>{{ __('Save User') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script src="{{ asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();

            const selectRole = $("#role");
            const fileCV = $("#fileCV");
            const bio = $("#inputBio");
            const avatarInput = $('#avatar');
            const backgroundInput = $('#background');

            $("#username").on("input", function() {
                let currentValue = $(this).val();
                let newValue = currentValue.toLowerCase().replace(/[^a-z0-9]/g, "");
                $(this).val(newValue);
            });

            selectRole.on("change", function() {
                if (selectRole.val() === "penulis") {
                    fileCV.removeClass('d-none').addClass('d-block');
                    bio.removeClass('d-none').addClass('d-block');
                } else if (selectRole.val() === "pembaca") {
                    fileCV.removeClass('d-block').addClass('d-none');
                    bio.removeClass('d-block').addClass('d-none');
                } else {
                    fileCV.removeClass('d-block').addClass('d-none');
                    bio.removeClass('d-block').addClass('d-none');
                }
            });

            if (selectRole.val() === "penulis") {
                fileCV.removeClass('d-none').addClass('d-block');
                bio.removeClass('d-none').addClass('d-block');
            } else if (selectRole.val() === "pembaca") {
                fileCV.removeClass('d-block').addClass('d-none');
                bio.removeClass('d-block').addClass('d-none');
            } else {
                fileCV.removeClass('d-block').addClass('d-none');
                bio.removeClass('d-block').addClass('d-none');
            }

            avatarInput.on('change', function() {
                previewAvatarImage(this);
            });

            backgroundInput.on('change', function() {
                previewBackgroundImage(this);
            });

            function previewAvatarImage(input) {
                const file = input.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#avatarView').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            }

            function previewBackgroundImage(input) {
                const file = input.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#backgroundView').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endprepend
