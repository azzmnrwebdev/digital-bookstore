@extends('admin.index')
@section('title-head', 'Detail User')
@section('title-content', 'Detail User')

@prepend('styles')
    <style>
        .background-image {
            z-index: -1;
            width: 100%;
            height: 180px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .profile-image {
            width: 125px;
            height: 125px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 50%;
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

        p.text-bio {
            color: #5F6573;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        p.text-bio.expanded {
            -webkit-line-clamp: initial;
        }

        p.toggle-bio {
            display: block;
            margin-top: 5px;
        }

        @media (min-width: 576px) {
            h3.text-fullname {
                font-size: 24px;
            }

            h5.text-information {
                font-size: 16px;
            }

            p.text-bio {
                font-size: 16px;
            }

            .background-image {
                height: 210px;
            }
        }

        @media (min-width: 768px) {
            .background-image {
                height: 240px;
            }

            .profile-image {
                width: 150px;
                height: 150px;
            }
        }

        @media (min-width: 992px) {
            .background-image {
                height: 270px;
            }
        }

        @media (min-width: 1200px) {
            .background-image {
                height: 300px;
            }
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('manageuser.index') }}">{{ __('Manage User') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Detail') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-body">
                    <div class="position-relative" style="margin-bottom: 75px;">
                        <img src="{{ $model->background ? asset($model->background) : asset('assets/image/no-image.png') }}"
                            class="background-image rounded" alt="Background Image">

                        <img src="{{ $model->avatar ? asset($model->avatar) : asset('assets/image/empty.jpg') }}"
                            class="profile-image position-absolute"
                            style="bottom: -75px; left: 50%; transform: translateX(-50%); z-index: 1;" alt="Foto Profile">
                    </div>

                    <div class="row justify-content-md-center" style="margin-top: 6rem !important;">
                        <div class="col-12 col-md-10 col-xl-8 p-0 mb-3">
                            <div class="card mb-0 shadow-none text-center">
                                <div class="card-body p-0">
                                    <h3 class="text-fullname text-center">{{ $manageuser->fullname }}</h3>
                                    <h6 class="text-center">
                                        @if ($manageuser->role === 'admin')
                                            <span class="badge badge-dark">{{ __('Admin') }}</span>
                                        @elseif ($manageuser->role === 'penulis')
                                            <span class="badge"
                                                style="background-color: #FF4081; color: #f3f3f3;">{{ __('Penulis') }}</span>
                                        @else
                                            <span class="badge"
                                                style="background-color: #3F51B5; color: #f3f3f3;">{{ __('Pembaca') }}</span>
                                        @endif
                                        {{ __('|') }} @if ($manageuser->is_active === 1)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('InActive') }}</span>
                                        @endif
                                    </h6>

                                    <h5 class="text-information text-center m-0">
                                        {{ __('@') }}{{ $manageuser->username }}
                                        {{ __('|') }} {{ $manageuser->email }} @if ($manageuser->phone_number)
                                            {{ __('|') }} {{ $manageuser->phone_number }}
                                            @endif @if ($manageuser->gender)
                                                @if ($manageuser->gender === 'L')
                                                    {{ __('|') }} {{ __('Male') }}
                                                @elseif ($manageuser->gender === 'P')
                                                    {{ __('|') }} {{ __('Female') }}
                                                @endif
                                            @endif
                                            @if ($manageuser->tgl_lahir)
                                                {{ __('|') }}
                                                {{ \Carbon\Carbon::parse($manageuser->tgl_lahir)->format('l, d F Y') }}
                                            @endif
                                    </h5>

                                    @if ($manageuser->role === 'penulis')
                                        <p class="text-bio text-center" style="line-height: 24px;">{{ $model->bio }}</p>
                                        @if (strlen(strip_tags($model->bio)) > 120)
                                            <button class="toggle-bio btn btn-sm btn-dark">Read more</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="mb-0" />
                        </div>

                        @if ($manageuser->role === 'penulis')
                            <div class="col-12" style="margin-top: 2.5rem;">
                                <div class="card shadow-none">
                                    <div class="card-header"
                                        style="background-color: #0f172a; color: #f1f5f9; font-weight: 500;">
                                        <h3 class="card-title">{{ __('Curriculum Vitae (CV)') }}</h3>
                                    </div>

                                    <div class="card-body p-0">
                                        <embed src="{{ asset($manageuser->author->cv) }}" type="application/pdf"
                                            width="100%" height="500px" class="mt-1" />
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script>
        $(function() {
            $('.toggle-bio').click(function() {
                let $bio = $(this).prev('.text-bio');
                $bio.toggleClass('expanded');
                if ($bio.hasClass('expanded')) {
                    $bio.css('overflow', 'visible');
                    $(this).text('Show less');
                } else {
                    $bio.css('overflow', 'hidden');
                    $(this).text('Read more');
                }
            });
        });
    </script>
@endprepend
