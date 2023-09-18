@extends('admin.index')
@section('title-head', 'Admin Profile')
@section('title-content', 'Admin Profile')

@prepend('styles')
    <style>
        .background-image {
            z-index: -1;
            width: 100%;
            height: 150px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .profile-image {
            left: 50%;
            z-index: 1;
            width: 100px;
            height: 100px;
            bottom: -50px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transform: translateX(-50%);
        }

        .mb-custom {
            margin-bottom: 50px;
        }

        .mt-profile {
            margin-top: 4rem;
        }

        h3.text-fullname {
            color: #020617;
            font-weight: 500;
            font-size: 22px;
        }

        h5.text-information {
            color: #5F6573;
            font-weight: 500;
            font-size: 14px;
        }

        @media (min-width: 576px) {
            h3.text-fullname {
                font-size: 24px;
            }

            h5.text-information {
                font-size: 16px;
            }

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

            .mt-profile {
                margin-top: 5rem;
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

            .mt-profile {
                margin-top: 6rem;
            }
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Admin Profile') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    <div class="position-relative mb-custom">
                        <img src="{{ $user->profile->background ? asset($user->profile->background) : asset('assets/image/no-image.png') }}"
                            class="background-image rounded" alt="Background Image">

                        <img src="{{ $user->profile->avatar ? asset($user->profile->avatar) : asset('assets/image/empty.jpg') }}"
                            class="profile-image position-absolute" alt="Foto Profile">
                    </div>

                    <div class="row justify-content-md-center mt-profile">
                        <div class="col-12 col-md-10 col-xl-8 p-0 mb-3">
                            <div class="card mb-0 shadow-none text-center">
                                <div class="card-body p-0">
                                    <h3 class="text-fullname text-center">{{ $user->fullname }}</h3>
                                    <h6 class="text-center"><span class="badge badge-dark">{{ ucfirst($user->role) }}</span>
                                        {{ __('|') }} @if ($user->is_active === 1)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('InActive') }}</span>
                                        @endif
                                    </h6>

                                    <h5 class="text-information m-0 text-center">{{ __('@') }}{{ $user->username }}
                                        {{ __('|') }} {{ $user->email }} @if ($user->phone_number)
                                            {{ __('|') }} {{ $user->phone_number }}
                                            @endif @if ($user->gender)
                                                @if ($user->gender === 'L')
                                                    {{ __('|') }} {{ __('Male') }}
                                                @elseif ($user->gender === 'P')
                                                    {{ __('|') }} {{ __('Female') }}
                                                @endif
                                            @endif
                                            @if ($user->tgl_lahir)
                                                {{ __('|') }}
                                                {{ \Carbon\Carbon::parse($user->tgl_lahir)->format('l, d F Y') }}
                                            @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="mb-0" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
@endprepend
