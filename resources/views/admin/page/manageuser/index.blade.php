@extends('admin.index')
@section('title-head', 'Manage User')
@section('title-content', 'Manage User')

@prepend('styles')
    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />

    <style>
        .more-info-row {
            display: none;
        }

        .hoverable-row:hover,
        .hoverable-row.opened {
            background-color: #f2feff;
        }

        .align-middle {
            vertical-align: middle !important
        }

        #search:focus {
            border-color: #42B549;
        }

        .thead-custom {
            color: #ffffff;
            background-color: #00ACC1;
        }

        .link-fullname {
            color: #42B549;
            font-weight: 500;
        }

        .hoverable-row:hover .link-fullname,
        .hoverable-row.opened .link-fullname {
            color: #2E912F;
            text-decoration: underline;
        }
    </style>
@endprepend

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Manage User') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 4px solid #42B549;">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('All User') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('manageuser.create') }}"
                                class="btn btn-sm btn-dark bg-gradient">{{ __('Create') }}</a>
                            &nbsp;
                            <a href="{{ route('manageuser.pdf') }}" class="btn btn-sm btn-danger"
                                title="Export to PDF">{{ __('Export PDF') }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (Session('success'))
                        <div id="alert-msg" class="alert alert-success alert-dismissible mb-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session('success') }}
                        </div>
                    @endif

                    @if (Session('error'))
                        <div id="alert-msg" class="alert alert-danger alert-dismissible mb-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ Session('error') }}
                        </div>
                    @endif

                    <label for="search">{{ __('Search User') }}</label>
                    <input type="search" name="search" id="search" value="{{ $search }}" class="form-control"
                        placeholder="Search by fullname, username or email">

                    <div class="table-responsive mt-3">
                        <table class="table text-nowrap border-bottom">
                            <thead class="thead-custom">
                                <tr>
                                    <th class="text-center">{{ __('No') }}</th>
                                    <th class="text-left">{{ __('Fullname') }}</th>
                                    <th class="text-left">{{ __('Username') }}</th>
                                    <th class="text-left">{{ __('Email') }}</th>
                                    <th class="text-center">{{ __('Role') }}</th>
                                    <th class="text-center">{{ __('Active') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Join Date') }}</th>
                                    <th class="text-center">{{ __('Detail') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->count() > 0)
                                    @php $number = ($users->currentpage()-1) * $users->perpage() + 1; @endphp
                                    @foreach ($users as $row)
                                        <tr class="hoverable-row">
                                            <td class="text-center align-middle">{{ $number++ }}</td>

                                            <td class="align-middle">
                                                <a href="{{ route('manageuser.show', $row->username) }}"
                                                    class="link-fullname">{{ $row->fullname }}</a>
                                            </td>

                                            <td class="align-middle">{{ $row->username }}</td>
                                            <td class="align-middle">{{ $row->email }}</td>

                                            <td class="text-center align-middle">
                                                @if ($row->role == 'admin')
                                                    <span class="badge badge-dark">{{ __('Admin') }}</span>
                                                @elseif ($row->role == 'penulis')
                                                    <span class="badge"
                                                        style="background-color: #FF4081; color: #f3f3f3;">{{ __('Penulis') }}</span>
                                                @else
                                                    <span class="badge"
                                                        style="background-color: #3F51B5; color: #f3f3f3;">{{ __('Pembaca') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                @if ($row->is_active === 1)
                                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('InActive') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                @if ($row->status === 'process')
                                                    <span class="badge badge-warning">{{ __('Process') }}</span>
                                                @elseif ($row->status === 'approved')
                                                    <span class="badge badge-success">{{ __('Approved') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($row->created_at)->format('d F Y') }}</td>

                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-outline-secondary more-button">
                                                    <i class="fas fa-caret-down"></i></button>
                                            </td>
                                        </tr>

                                        <tr class="more-info-row">
                                            <td colspan="9">
                                                <div class="card m-0 my-3">
                                                    <div class="card-body p-0">
                                                        <table class="table">
                                                            <thead style="background-color: #f0fdf4">
                                                                <tr>
                                                                    <th class="text-left">{{ __('Gender') }}</th>
                                                                    <th class="text-left">{{ __('Phone Number') }}</th>
                                                                    <th class="text-center">{{ __('Date of Birth') }}</th>
                                                                    <th class="text-center">{{ __('Created') }}</th>
                                                                    <th class="text-center">{{ __('Updated') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        @if ($row->gender === 'L')
                                                                            {{ __('Male') }}
                                                                        @elseif ($row->gender === 'P')
                                                                            {{ __('Female') }}
                                                                        @else
                                                                            {{ __('Empty') }}
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-left">
                                                                        {{ $row->phone_number ? $row->phone_number : 'Empty' }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $row->tgl_lahir ? \Carbon\Carbon::parse($row->tgl_lahir)->format('l, d F Y') : 'Empty' }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $row->getTimeAgo($row->created_at) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $row->getTimeAgo($row->updated_at) }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="card m-0 mb-3">
                                                    <div class="card-body p-0">
                                                        <table class="table">
                                                            <thead style="background-color: #f0fdf4">
                                                                <tr>
                                                                    @if ($row->role === 'penulis')
                                                                        <th class="text-left">{{ __('Bio') }}</th>
                                                                    @endif

                                                                    <th class="text-center">{{ __('Avatar') }}</th>
                                                                    <th class="text-center">{{ __('Background') }}</th>

                                                                    @if ($row->role === 'penulis')
                                                                        <th class="text-center">{{ __('CV') }}</th>
                                                                    @endif

                                                                    <th class="text-center">{{ __('Action') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    @if ($row->role === 'penulis')
                                                                        <td class="text-wrap">
                                                                            {{ $row->author->bio ? $row->author->bio : 'Empty' }}
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if ($row->author->avatar)
                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#penulisAvatarModal{{ $row->id }}">{{ __('Preview') }}</a>

                                                                                <div class="modal fade"
                                                                                    id="penulisAvatarModal{{ $row->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="penulisAvatarModalLabel{{ $row->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-scrollable"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="penulisAvatarModalLabel{{ $row->id }}">
                                                                                                    {{ __('Profile Picture') }}
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($row->author->avatar) }}"
                                                                                                    alt="Profile Picture"
                                                                                                    class="img-fluid"
                                                                                                    loading="lazy">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-dismiss="modal">{{ __('Close') }}</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                {{ __('Empty') }}
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if ($row->author->background)
                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#penulisBackgroundModal{{ $row->id }}">{{ __('Preview') }}</a>

                                                                                <div class="modal fade"
                                                                                    id="penulisBackgroundModal{{ $row->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="penulisBackgroundModalLabel{{ $row->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-scrollable"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="penulisBackgroundModalLabel{{ $row->id }}">
                                                                                                    {{ __('Background Photo') }}
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($row->author->background) }}"
                                                                                                    alt="Background Photo"
                                                                                                    class="img-fluid"
                                                                                                    loading="lazy">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-dismiss="modal">{{ __('Close') }}</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                {{ __('Empty') }}
                                                                            @endif
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center">
                                                                            @if ($row->profile->avatar)
                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#pembacaAvatarModal{{ $row->id }}">{{ __('Preview') }}</a>

                                                                                <div class="modal fade"
                                                                                    id="pembacaAvatarModal{{ $row->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="pembacaAvatarModalLabel{{ $row->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-scrollable"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="pembacaAvatarModalLabel{{ $row->id }}">
                                                                                                    {{ __('Profile Picture') }}
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($row->profile->avatar) }}"
                                                                                                    alt="Profile Picture"
                                                                                                    class="img-fluid"
                                                                                                    loading="lazy">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-dismiss="modal">{{ __('Close') }}</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                {{ __('Empty') }}
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if ($row->profile->background)
                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#pembacaBackgroundModal{{ $row->id }}">{{ __('Preview') }}</a>

                                                                                <div class="modal fade"
                                                                                    id="pembacaBackgroundModal{{ $row->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="pembacaBackgroundModalLabel{{ $row->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-scrollable"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="pembacaBackgroundModalLabel{{ $row->id }}">
                                                                                                    {{ __('Background Photo') }}
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <img src="{{ asset($row->profile->background) }}"
                                                                                                    alt="Background Photo"
                                                                                                    class="img-fluid"
                                                                                                    loading="lazy">
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-dismiss="modal">{{ __('Close') }}</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                {{ __('Empty') }}
                                                                            @endif
                                                                        </td>
                                                                    @endif

                                                                    @if ($row->role === 'penulis')
                                                                        <td class="text-center">
                                                                            <a href="{{ asset($row->author->cv) }}"
                                                                                class="btn btn-sm btn-primary"
                                                                                download>{{ __('Download CV') }}</a>
                                                                        </td>
                                                                    @endif

                                                                    <td class="text-center">
                                                                        <form
                                                                            action="{{ route('manageuser.destroy', $row->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <div class="btn-group">
                                                                                <a class="btn btn-sm btn-warning bg-gradient"
                                                                                    href="{{ route('manageuser.edit', $row->username) }}"><i
                                                                                        class="fa fa-pencil-alt"></i></a>
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-danger bg-gradient"
                                                                                    onclick="return confirm('Are you sure you want to delete this account?')"><i
                                                                                        class="fa fa-trash"></i></button>
                                                                            </div>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <img src="{{ asset('assets/image/empty-data.svg') }}" class="mb-4 w-25"
                                                alt="Empty">
                                            <h4>{{ __('No users found') }}</h4>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-6 mx-auto">
                <div class="d-flex align-content-center justify-content-center w-100">
                    {{ $users->appends($_GET)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    {{-- toastr js --}}
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

    <script>
        $(function() {
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

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif

            $('.hoverable-row .more-button').on('click', function() {
                let row = $(this).closest('tr');
                let moreInfoRow = row.next('.more-info-row');
                let moreButton = $(this);

                if (row.hasClass('opened')) {
                    row.removeClass('opened');
                    moreInfoRow.hide();
                    moreButton.find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
                    moreButton.removeClass('btn-secondary').addClass('btn-outline-secondary');
                } else {
                    $('.hoverable-row.opened').not(row).each(function() {
                        let otherRow = $(this);
                        let otherMoreInfoRow = otherRow.next('.more-info-row');
                        let otherMoreButton = otherRow.find('.more-button');

                        otherRow.removeClass('opened');
                        otherMoreInfoRow.hide();
                        otherMoreButton.find('i').removeClass('fa-caret-up').addClass(
                            'fa-caret-down');
                        otherMoreButton.removeClass('btn-secondary').addClass(
                            'btn-outline-secondary');
                    });

                    row.addClass('opened');
                    moreInfoRow.show();
                    moreButton.find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
                    moreButton.removeClass('btn-outline-secondary').addClass('btn-secondary');
                }
            });

            $('#search').on('input', function() {
                filter();
            });

            function filter() {
                const searchValue = $('#search').val();
                const params = {};
                const url = '{{ route('manageuser.index') }}';

                if (searchValue.trim() !== '') {
                    params.q = encodeURIComponent(searchValue.trim());
                }

                const queryString = Object.keys(params).map(key => key + '=' + params[key]);

                const finalUrl = url + '?' + queryString;
                window.location.href = finalUrl;
            }
        });
    </script>
@endprepend
