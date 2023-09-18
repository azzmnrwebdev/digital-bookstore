@extends('home.index')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" />

    <style>
        main {
            padding: 4rem 0 !important;
        }

        .background {
            width: 100%;
            height: 160px;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .avatar-container {
            width: 125px;
            height: 125px;
            overflow: hidden;
        }

        .avatar-container .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile .card-title.profile,
        .profile .card-title.ebook {
            font-size: 20px;
            font-weight: 600;
            color: #151515;
        }

        .table-bordered {
            border-color: #42B549;
        }

        .thead-custom {
            color: #444444;
            background-color: #9efda4;
        }

        .hoverable-row:hover {
            background-color: #e3ffe5;
        }

        @media (min-width: 576px) {}

        @media (min-width: 768px) {
            .background {
                height: 200px;
            }

            .avatar-container {
                width: 140px;
                height: 140px;
            }
        }

        @media (min-width: 992px) {
            .background {
                height: 250px;
            }

            .avatar-container {
                width: 150px;
                height: 150px;
            }
        }

        @media (min-width: 1200px) {
            .background {
                height: 300px;
            }
        }
    </style>
@endprepend

@section('main')
    <main>
        <section>
            <div class="container">
                @if (Session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session('success') }}
                    </div>
                @endif

                {{-- background --}}
                @if ($pembaca->background)
                    <img src="{{ asset($pembaca->background) }}" class="background rounded mb-3" alt="Background Image">
                @else
                    <img src="{{ asset('assets/image/no-image.png') }}" class="background rounded mb-3"
                        alt="Background Image">
                @endif

                <div class="card">
                    <div class="card-body px-md-5 py-5">
                        {{-- avatar --}}
                        <center>
                            <div class="avatar-container">
                                @if ($pembaca->avatar)
                                    <img src="{{ asset($pembaca->avatar) }}" class="avatar" alt="Avatar Image">
                                @else
                                    <img src="{{ asset('assets/image/empty.jpg') }}" class="avatar" alt="Avatar Image">
                                @endif
                            </div>
                        </center>

                        {{-- profil --}}
                        <div class="profile mt-4">
                            <h5 class="card-title profile">Profile</h5>

                            <div class="table-responsive">
                                <table class="table text-nowrap table-bordered align-middle">
                                    <thead class="thead-custom">
                                        <tr>
                                            <th class="text-start" style="font-weight: 500 !important;">Fullname</th>
                                            <th class="text-start" style="font-weight: 500 !important;">Username</th>
                                            <th class="text-start" style="font-weight: 500 !important;">Email</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Gender</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Phone Number</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Date of Birth</th>
                                            <th class="text-center" style="font-weight: 500 !important;">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="hoverable-row">
                                            <td class="text-start">{{ $pembaca->user->fullname }}</td>
                                            <td class="text-start">{{ $pembaca->user->username }}</td>
                                            <td class="text-start">{{ $pembaca->user->email }}</td>
                                            <td class="text-center">
                                                @if ($pembaca->user->gender === 'L')
                                                    Male
                                                @elseif ($pembaca->user->gender === 'P')
                                                    Female
                                                @elseif ($pembaca->user->gender === 'Other')
                                                    Other
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $pembaca->user->phone_number ? $pembaca->user->phone_number : '-' }}</td>
                                            <td class="text-center">
                                                {{ $pembaca->user->tgl_lahir ? \Carbon\Carbon::parse($pembaca->user->tgl_lahir)->format('l, d F Y') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($pembaca->user->is_active === 1)
                                                    <span class="badge text-bg-success">Active</span>
                                                @else
                                                    <span class="badge text-bg-danger">InActive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- button untuk edit profile --}}
                            <a href="{{ route('pembaca.profile_edit', $pembaca->user->username) }}" class="btn btn-sm btn-warning">Edit Profile</a>

                            {{-- button change password --}}
                            <a href="{{ route('pembaca.profile_change_password') }}" class="btn btn-sm btn-dark">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "8000",
            "extendedTimeOut": "8000",
        }

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif
    </script>
@endprepend
