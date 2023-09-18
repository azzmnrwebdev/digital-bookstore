@extends('penulis.index')
@section('title-head', 'Update Profile')
@section('title-content', 'Update Profile')

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
    <li class="breadcrumb-item"><a href="{{ route('penulis.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Account Setting') }}</li>
    <li class="breadcrumb-item active">{{ __('Update Profile') }}</li>
@endsection

@section('content')
    <div class="card" style="border-top: 4px solid #42B549;">
        <div class="card-body">
            {{-- image preview --}}
            <div class="card mb-4 shadow-none">
                <div class="card-header bg-dark">
                    <h3 class="card-title">{{ __('Image Preview') }}</h3>
                </div>

                <div class="card-body px-0 pb-0 pt-2">
                    <div class="position-relative mb-custom">
                        <img src="{{ $author->background ? asset($author->background) : asset('assets/image/no-image.png') }}"
                            id="backgroundView" class="background-image rounded" alt="Background Image">

                        <img src="{{ $author->avatar ? asset($author->avatar) : asset('assets/image/empty.jpg') }}"
                            id="avatarView" class="profile-image position-absolute" style="" alt="Foto Profile">
                    </div>
                </div>
            </div>

            <form action="{{ route('penulis.update_profile', $userLogin->id) }}" method="post"
                enctype="multipart/form-data" class="mt-5">
                @csrf
                @method('put')

                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label for="fullname">{{ __('Fullname') }} <span
                                class="text-danger">{{ __('*') }}</span></label>
                        <input type="text" name="fullname" id="fullname"
                            class="form-control @error('fullname') is-invalid @enderror"
                            value="{{ old('fullname', $userLogin->fullname) }}" placeholder="Fullname">

                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="username">{{ __('Username') }} <span
                                class="text-danger">{{ __('*') }}</span></label>
                        <input type="text" name="username" id="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $userLogin->username) }}" placeholder="Username">

                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="email">{{ __('Email') }} <span
                                class="text-danger">{{ __('*') }}</span></label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $userLogin->email) }}" placeholder="Email, cth: example@gmail.com">

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="phone_number">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="form-control @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number', $userLogin->phone_number) }}"
                            placeholder="Cth: 08123456789123">

                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="gender" class="form-label">{{ __('Gender') }}</label>

                        <select name="gender" id="gender" class="form-control">
                            <option value="" {{ old('gender') }} @if ($userLogin->gender === null) selected @endif>
                                {{ __('-- Select Gender --') }}
                            </option>

                            @foreach (['L' => 'Male', 'P' => 'Female', 'Other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}" {{ old('gender') }}
                                    @if ($userLogin->gender === $value) selected @endif>
                                    {{ __($label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="tgl_lahir" class="form-label">{{ __('Date of Birth') }}</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir"
                            class="form-control @error('tgl_lahir') is-invalid @enderror"
                            value="{{ old('tgl_lahir', $userLogin->tgl_lahir) }}" max="2006-12-31">

                        @error('tgl_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label for="bio" class="form-label">{{ __('Bio') }}</label>
                        <textarea name="bio" id="bio" class="form-control" rows="5" placeholder="Bio">{{ old('bio', $author->bio) }}</textarea>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
                        <div class="custom-file">
                            <input type="file" name="avatar" id="avatar"
                                class="custom-file-input @error('avatar') is-invalid @enderror"
                                accept=".png, .jpg, .jpeg">
                            <label class="custom-file-label"
                                for="avatar">{{ $author->avatar ? 'Change avatar' : 'Choose avatar' }}</label>

                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="background" class="form-label">{{ __('Background') }}</label>
                        <div class="custom-file">
                            <input type="file" name="background" id="background"
                                class="custom-file-input @error('background') is-invalid @enderror"
                                accept=".png, .jpg, .jpeg">
                            <label class="custom-file-label"
                                for="background">{{ $author->background ? 'Change background' : 'Choose background' }}</label>

                            @error('background')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label for="cv" class="form-label">{{ __('Curriculum Vitae (CV)') }} <span
                                class="text-danger">{{ __('*') }}</span></label>
                        <div class="custom-file">
                            <input type="file" name="cv" id="cv"
                                class="custom-file-input @error('cv') is-invalid @enderror" accept=".pdf">
                            <label class="custom-file-label"
                                for="cv">{{ $author->cv ? 'Change file' : 'Choose file' }}</label>

                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-sm btn-warning"><i
                        class="fas fa-pencil-alt mr-3"></i>{{ __('Update Profile') }}</button>
            </form>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- bootstrap custom file --}}
    <script src="{{ asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        bsCustomFileInput.init();

        $("#username").on("input", function() {
            let currentValue = $(this).val();
            let newValue = currentValue.toLowerCase().replace(/[^a-z0-9]/g, "");
            $(this).val(newValue);
        });

        $("#email").on("input", function() {
            let currentValue = $(this).val();
            let newValue = currentValue.toLowerCase().replace(/[^a-z0-9.@]/g, "");

            const atIndex = newValue.indexOf("@");
            if (atIndex !== -1) {
                const username = newValue.slice(0, atIndex);
                const domain = newValue.slice(atIndex + 1);
                newValue = username + "@" + domain.replace(/@/g, "");
            }

            $(this).val(newValue);
        });

        $('#avatar').on('change', function() {
            previewAvatarImage(this);
        });

        $('#background').on('change', function() {
            previewBackgroundImage(this);
        });

        function previewAvatarImage(input) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#avatarView').attr('src', e.target.result)
            };
            reader.readAsDataURL(file);
        }

        function previewBackgroundImage(input) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#backgroundView').attr('src', e.target.result)
            };
            reader.readAsDataURL(file);
        }
    </script>
@endprepend
