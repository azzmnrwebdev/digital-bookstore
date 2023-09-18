@extends('admin.index')
@section('title-head', 'Edit User')
@section('title-content', 'Edit User')

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
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
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
                                <img src="{{ $model->background ? asset($model->background) : asset('assets/image/no-image.png') }}"
                                    id="backgroundView" class="background-image rounded" alt="Background Image">

                                <img src="{{ $model->avatar ? asset($model->avatar) : asset('assets/image/empty.jpg') }}"
                                    id="avatarView" class="profile-image position-absolute" style=""
                                    alt="Foto Profile">
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('manageuser.update', $manageuser->id) }}" method="post"
                        enctype="multipart/form-data" class="mt-5">
                        @csrf
                        @method('put')

                        <div class="row">
                            {{-- fullname --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="fullname" class="form-label">{{ __('Fullname') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" name="fullname" id="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    value="{{ old('fullname', $manageuser->fullname) }}" placeholder="Fullname">

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
                                    value="{{ old('username', $manageuser->username) }}" placeholder="Username">

                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- email --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="email" class="form-label">{{ __('Email') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $manageuser->email) }}" placeholder="Email">

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- no handphone --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="phone_number" class="form-label">{{ __('Phone Number') }}</label>
                                <input type="tel" name="phone_number" id="phoneNumber"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number', $manageuser->phone_number) }}"
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
                                    <option value="" {{ old('gender') }}
                                        @if ($manageuser->gender === null) selected @endif>
                                        {{ __('-- Select Gender --') }}
                                    </option>

                                    @foreach (['L' => 'Male', 'P' => 'Female', 'Other' => 'Other'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('gender') }}
                                            @if ($manageuser->gender === $value) selected @endif>
                                            {{ __($label) }}
                                        </option>
                                    @endforeach
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
                                    value="{{ old('tgl_lahir', $manageuser->tgl_lahir) }}" max="2006-12-31">

                                @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- is active --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="is_active" class="form-label">{{ __('Active') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>

                                <select name="is_active" id="is_active"
                                    class="form-control @error('is_active') is-invalid @enderror"
                                    @if ($manageuser->role === 'admin') disabled @endif>
                                    <option value="1"
                                        {{ old('is_active', $manageuser->is_active) === 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}</option>
                                    <option value="0"
                                        {{ old('is_active', $manageuser->is_active) === 0 ? 'selected' : '' }}>
                                        {{ __('InActive') }}</option>
                                </select>

                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- status --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="status" class="form-label">{{ __('Status') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>

                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    @if ($manageuser->status === 'approved' || $manageuser->status === 'rejected') disabled @endif>
                                    <option value="process"
                                        {{ old('status', $manageuser->status) === 'process' ? 'selected' : '' }}>
                                        {{ __('Process') }}</option>
                                    <option value="approved"
                                        {{ old('status', $manageuser->status) === 'approved' ? 'selected' : '' }}>
                                        {{ __('Approved') }}</option>
                                    <option value="rejected"
                                        {{ old('status', $manageuser->status) === 'rejected' ? 'selected' : '' }}>
                                        {{ __('Rejected') }}</option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- password lama --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="old_password" class="form-label">{{ __('Old Password') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="password" name="old_password" id="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror"
                                    value="{{ old('old_password') }}" placeholder="Old Password">

                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- password baru --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="new_password" class="form-label">{{ __('New Password') }} <span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    value="{{ old('new_password') }}" placeholder="New Password">

                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- bio --}}
                            @if ($manageuser->role === 'penulis')
                                <div class="form-group col-12">
                                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                    <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="5">{{ old('bio', $model->bio) }}</textarea>

                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- foto profile --}}
                            <div class="form-group col-12 col-md-6">
                                <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="avatar" id="avatar"
                                        class="custom-file-input @error('avatar') is-invalid @enderror"
                                        accept=".png, .jpg, .jpeg">
                                    <label class="custom-file-label"
                                        for="avatar">{{ $model->avatar ? 'Change avatar' : 'Choose avatar' }}</label>

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
                                        for="background">{{ $model->background ? 'Change background' : 'Choose background' }}</label>

                                    @error('background')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- cv --}}
                            @if ($manageuser->role === 'penulis')
                                <div class="form-group col-12">
                                    <label for="cv" class="form-label">{{ __('Curriculum Vitae (CV)') }} <span
                                            class="text-danger">{{ __('*') }}</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="cv" id="cv"
                                            class="custom-file-input @error('cv') is-invalid @enderror" accept=".pdf">
                                        <label class="custom-file-label"
                                            for="cv">{{ $model->cv ? 'Change file' : 'Choose file' }}</label>

                                        @error('cv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-sm btn-warning"><i
                                class="fas fa-pencil-alt mr-3"></i>{{ __('Update User') }}</button>
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

            const avatarInput = $('#avatar');
            const backgroundInput = $('#background');

            $("#username").on("input", function() {
                let currentValue = $(this).val();
                let newValue = currentValue.toLowerCase().replace(/[^a-z0-9]/g, "");
                $(this).val(newValue);
            });

            $("#username").on("input", function() {
                let currentValue = $(this).val();
                let newValue = currentValue.toLowerCase().replace(/[^a-z0-9]/g, "");
                $(this).val(newValue);
            });

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
